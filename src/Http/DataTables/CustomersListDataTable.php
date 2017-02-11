<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Http\DataTables;

use Illuminate\Database\Eloquent\SoftDeletes;
use WebEd\Base\Core\Http\DataTables\AbstractDataTables;
use WebEd\Base\Core\Models\EloquentBase;
use WebEd\Plugins\Ecommerce\Addons\Customers\Models\Customer;
use WebEd\Plugins\Ecommerce\Addons\Customers\Repositories\CustomerRepository;

class CustomersListDataTable extends AbstractDataTables
{
    /**
     * @var Customer
     */
    protected $model;

    /**
     * @var array|\Illuminate\Http\Request|string
     */
    protected $request;

    public function __construct()
    {
        $this->model = Customer::select('id', 'created_at', 'avatar', 'username', 'email', 'status', 'sex', 'deleted_at');

        parent::__construct();

        $this->request = request();
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->setAjaxUrl(route('admin::ecommerce.customers.index.post'), 'POST');

        $this
            ->addHeading('avatar', 'Avatar', '1%')
            ->addHeading('username', 'Username', '15%')
            ->addHeading('email', 'Email', '15%')
            ->addHeading('status', 'Status', '10%')
            ->addHeading('created_at', 'Created at', '15%')
            ->addHeading('actions', 'Actions', '25%');

        $this
            ->addFilter(2, form()->text('username', '', [
                'class' => 'form-control form-filter input-sm',
                'placeholder' => 'Search...'
            ]))
            ->addFilter(3, form()->email('email', '', [
                'class' => 'form-control form-filter input-sm',
                'placeholder' => 'Search...'
            ]))
            ->addFilter(4, form()->select('status', [
                '' => '',
                'activated' => 'Activated',
                'disabled' => 'Disabled',
                'deleted' => 'Deleted',
            ], '', ['class' => 'form-control form-filter input-sm']));

        $this->withGroupActions([
            '' => 'Select...',
            'activated' => 'Activated',
            'disabled' => 'Disabled',
            'deleted' => 'Deleted',
        ]);

        $this->setColumns([
            ['data' => 'id', 'name' => 'id', 'searchable' => false, 'orderable' => false],
            ['data' => 'avatar', 'name' => 'avatar', 'searchable' => false, 'orderable' => false],
            ['data' => 'username', 'name' => 'username'],
            ['data' => 'email', 'name' => 'email'],
            ['data' => 'status', 'name' => 'status'],
            ['data' => 'created_at', 'name' => 'created_at', 'searchable' => false],
            ['data' => 'actions', 'name' => 'actions', 'searchable' => false, 'orderable' => false],
        ]);

        return $this->view();
    }

    /**
     * @return $this
     */
    protected function fetch()
    {
        $this->fetch = datatable()->of($this->model)
            ->editColumn('avatar', function ($item) {
                return '<img src="' . get_image($item->avatar) . '" width="50" height="50">';
            })
            ->editColumn('id', function ($item) {
                return form()->customCheckbox([['id[]', $item->id]]);
            })
            ->editColumn('status', function ($item) {
                return html()->label($item->status, $item->status);
            })
            ->addColumn('actions', function ($item) {
                /*Edit link*/
                $activeLink = route('admin::ecommerce.customers.update-status.post', ['id' => $item->id, 'status' => 'activated']);
                $disableLink = route('admin::ecommerce.customers.update-status.post', ['id' => $item->id, 'status' => 'disabled']);
                $deleteLink = route('admin::ecommerce.customers.delete.delete', ['id' => $item->id]);

                /*Buttons*/
                $editBtn = link_to(route('admin::ecommerce.customers.edit.get', ['id' => $item->id]), 'Edit', ['class' => 'btn btn-outline green btn-sm']);
                $activeBtn = ($item->status != 'activated') ? form()->button('Active', [
                    'title' => 'Active this item',
                    'data-ajax' => $activeLink,
                    'data-method' => 'POST',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline blue btn-sm ajax-link',
                ]) : '';
                $disableBtn = ($item->status != 'disabled') ? form()->button('Disable', [
                    'title' => 'Disable this item',
                    'data-ajax' => $disableLink,
                    'data-method' => 'POST',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline yellow-lemon btn-sm ajax-link',
                ]) : '';
                $deleteBtn = form()->button('Delete', [
                    'title' => 'Delete this item',
                    'data-ajax' => $deleteLink,
                    'data-method' => 'DELETE',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline red-sunglo btn-sm ajax-link',
                ]);

                $activeBtn = ($item->status != 'activated') ? $activeBtn : '';
                $disableBtn = ($item->status != 'disabled') ? $disableBtn : '';

                return $editBtn . $activeBtn . $disableBtn . $deleteBtn;
            });

        return $this;
    }
}
