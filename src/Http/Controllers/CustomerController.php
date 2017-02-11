<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Http\Controllers;

use WebEd\Base\Core\Http\Controllers\BaseAdminController;
use WebEd\Plugins\Ecommerce\Addons\Customers\Http\DataTables\CustomersListDataTable;
use WebEd\Plugins\Ecommerce\Addons\Customers\Http\Requests\CreateCustomerRequest;
use WebEd\Plugins\Ecommerce\Addons\Customers\Http\Requests\UpdateCustomerPasswordRequest;
use WebEd\Plugins\Ecommerce\Addons\Customers\Http\Requests\UpdateCustomerRequest;
use WebEd\Plugins\Ecommerce\Addons\Customers\Repositories\Contracts\CustomerRepositoryContract;
use WebEd\Plugins\Ecommerce\Addons\Customers\Repositories\CustomerRepository;
use Yajra\Datatables\Engines\BaseEngine;

class CustomerController extends BaseAdminController
{
    protected $module = 'webed-ecommerce-customers';

    /**
     * @var CustomerRepository
     */
    protected $repository;

    /**
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepositoryContract $customerRepository)
    {
        parent::__construct();

        $this->repository = $customerRepository;
        $this->breadcrumbs->addLink('Customers', route('admin::ecommerce.customers.index.get'));

        $this->getDashboardMenu($this->module);
    }

    public function getIndex(CustomersListDataTable $customersListDataTable)
    {
        $this->setPageTitle('All customers');

        $this->dis['dataTable'] = $customersListDataTable->run();

        return do_filter('ecommerce.customers.index.get', $this)->viewAdmin('index');
    }

    /**s
     * Get data for DataTable
     * @param CustomersListDataTable|BaseEngine $customersListDataTable
     * @return \Illuminate\Http\JsonResponse
     */
    public function postListing(CustomersListDataTable $customersListDataTable)
    {
        $data = $customersListDataTable->with($this->groupAction());

        return do_filter('datatables.ecommerce.customers.index.post', $data, $this);
    }

    /**
     * Handle group actions
     * @return array
     */
    protected function groupAction()
    {
        $data = [];
        if ($this->request->get('customActionType', null) == 'group_action') {
            $actionValue = $this->request->get('customActionValue', 'activated');

            $ids = collect($this->request->get('id', []))->filter(function ($value, $index) {
                return (int)$value !== (int)$this->loggedInUser->id;
            })->toArray();

            switch ($actionValue) {
                case 'deleted':
                    if (!has_permissions($this->loggedInUser, ['delete-customers'])) {
                        $data['customActionMessage'] = 'You do not have permission';
                        $data['customActionStatus'] = 'danger';
                        return $data;
                    }
                    $result = $this->repository->delete($ids);
                    break;
                default:
                    $result = $this->repository->updateMultiple($ids, [
                        'status' => $actionValue,
                    ], true);
                    break;
            }

            $data['customActionMessage'] = $result['messages'];
            $data['customActionStatus'] = $result['error'] ? 'danger' : 'success';
        }
        return $data;
    }

    /**
     * Update page status
     * @param $id
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUpdateStatus($id, $status)
    {
        $data = [
            'status' => $status
        ];

        $result = $this->repository->updateCustomer($id, $data);

        return response()->json($result, $result['response_code']);
    }

    public function getCreate()
    {
        $this->setPageTitle('Create customer');
        $this->breadcrumbs->addLink('Create customer');

        $this->dis['object'] = $this->repository->getModel();

        $this->assets
            ->addStylesheets('bootstrap-datepicker')
            ->addJavascripts('bootstrap-datepicker')
            ->addJavascriptsDirectly(asset('admin/modules/ecommerce/customers/customers.js'));

        $oldInputs = old();
        if ($oldInputs) {
            foreach ($oldInputs as $key => $row) {
                $this->dis['object']->$key = $row;
            }
        }

        return do_filter('ecommerce.customers.create.get', $this)->viewAdmin('create');
    }

    public function postCreate(CreateCustomerRequest $request)
    {
        $data = $request->except([
            '_token', '_continue_edit', '_tab', 'roles',
        ]);

        if ($request->exists('birthday') && !$request->get('birthday')) {
            $data['birthday'] = null;
        }

        $data['created_by'] = $this->loggedInUser->id;
        $data['updated_by'] = $this->loggedInUser->id;

        $result = $this->repository->createCustomer($data);

        $msgType = $result['error'] ? 'danger' : 'success';

        $this->flashMessagesHelper
            ->addMessages($result['messages'], $msgType)
            ->showMessagesOnSession();

        if ($result['error']) {
            return redirect()->back()->withInput();
        }

        do_action('ecommerce.customers.after-create.post', $result['data']->id, $result, $this);

        if ($request->has('_continue_edit')) {
            return redirect()->to(route('admin::ecommerce.customers.edit.get', ['id' => $result['data']->id]));
        }

        return redirect()->to(route('admin::ecommerce.customers.index.get'));
    }

    public function getEdit($id)
    {
        $item = $this->repository->find($id);

        if (!$item) {
            $this->flashMessagesHelper
                ->addMessages('Customer not found', 'danger')
                ->showMessagesOnSession();

            return redirect()->back();
        }

        $this->assets
            ->addStylesheets('bootstrap-datepicker')
            ->addJavascripts('bootstrap-datepicker')
            ->addJavascriptsDirectly(asset('admin/modules/ecommerce/customers/customers.js'));

        $this->setPageTitle('Edit customer', '#' . $id);
        $this->breadcrumbs->addLink('Edit customer');

        $this->dis['object'] = $item;

        return do_filter('ecommerce.customers.edit.get', $this, $id)->viewAdmin('edit');
    }

    public function postEdit(UpdateCustomerRequest $request, $id)
    {
        $customer = $this->repository->find($id);

        if (!$customer) {
            $this->flashMessagesHelper
                ->addMessages('Customer not found', 'danger')
                ->showMessagesOnSession();

            return redirect()->back();
        }

        $data = $request->except([
            '_token', '_continue_edit', '_tab', 'password',
        ]);

        if ($request->exists('birthday') && !$request->get('birthday')) {
            $data['birthday'] = null;
        }

        $data['updated_by'] = $this->loggedInUser->id;

        return $this->updateCustomer($customer, $data);
    }

    public function postUpdatePassword(UpdateCustomerPasswordRequest $request, $id)
    {
        $customer = $this->repository->find($id);

        if (!$customer) {
            $this->flashMessagesHelper
                ->addMessages('Customer not found', 'danger')
                ->showMessagesOnSession();

            return redirect()->back();
        }

        return $this->updateCustomer($customer, [
            'password' => $request->get('password'),
        ]);
    }

    protected function updateCustomer($customer, $data)
    {
        $result = $this->repository->updateCustomer($customer, $data);

        $msgType = $result['error'] ? 'danger' : 'success';

        $this->flashMessagesHelper
            ->addMessages($result['messages'], $msgType)
            ->showMessagesOnSession();

        if ($result['error']) {
            return redirect()->back();
        }

        do_action('ecommerce.customers.after-edit.post', $customer->id, $result, $this);

        if ($this->request->has('_continue_edit')) {
            return redirect()->back();
        }

        return redirect()->to(route('admin::ecommerce.customers.index.get'));
    }

    public function deleteDelete($id)
    {
        $result = $this->repository->delete($id);
        return response()->json($result, $result['response_code']);
    }
}
