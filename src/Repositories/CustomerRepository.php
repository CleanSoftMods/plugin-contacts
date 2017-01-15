<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Repositories;

use WebEd\Base\Core\Repositories\AbstractBaseRepository;
use WebEd\Base\Caching\Services\Contracts\CacheableContract;

use WebEd\Plugins\Ecommerce\Addons\Customers\Repositories\Contracts\CustomerRepositoryContract;

class CustomerRepository extends AbstractBaseRepository implements CustomerRepositoryContract, CacheableContract
{
    protected $rules = [
        'username' => 'required|between:3,100|string|unique:users|alpha_dash',
        'email' => 'required|between:5,255|email|unique:users',
        'password' => 'required|max:60|min:5|string',
        'status' => 'string|required|in:activated,disabled,deleted',
        'display_name' => 'string|between:1,150',
        'first_name' => 'string|between:1,100|required',
        'last_name' => 'string|between:1,100',
        'avatar' => 'string|between:1,150',
        'phone' => 'string|max:20',
        'mobile_phone' => 'string|max:20',
        'sex' => 'string|required|in:male,female,other',
        'birthday' => 'date_multi_format:Y-m-d H:i:s,Y-m-d|nullable',
        'description' => 'string|max:1000',
        'created_by' => 'integer|required|min:0',
        'updated_by' => 'integer|min:0',
        'last_login_at' => 'string|date_format:Y-m-d H:i:s',
        'last_activity_at' => 'string|date_format:Y-m-d H:i:s',
        'disabled_until' => 'string|date_format:Y-m-d H:i:s',
        'deleted_at' => 'string|date_format:Y-m-d H:i:s',
    ];

    protected $editableFields = [
        'username',
        'email',
        'password',
        'status',
        'display_name',
        'first_name',
        'last_name',
        'avatar',
        'phone',
        'mobile_phone',
        'sex',
        'birthday',
        'description',
        'created_by',
        'updated_by',
        'last_login_at',
        'last_activity_at',
        'disabled_until',
        'deleted_at',
    ];

    /**
     * @param array $data
     * @return array
     */
    public function createCustomer(array $data)
    {
        $resultEditObject = $this->editWithValidate(0, $data, true, false);

        if ($resultEditObject['error']) {
            return $this->setMessages($resultEditObject['messages'], true, \Constants::ERROR_CODE);
        }
        $object = $resultEditObject['data'];

        $result = $this->setMessages('Customer created successfully', false, \Constants::SUCCESS_CODE, $object);

        return $result;
    }

    /**
     * @param $id
     * @param array $data
     * @return array
     */
    public function updateCustomer($id, array $data)
    {
        $resultEditObject = $this->editWithValidate($id, $data, false, true);

        if ($resultEditObject['error']) {
            return $this->setMessages($resultEditObject['messages'], true, \Constants::ERROR_CODE);
        }
        $object = $resultEditObject['data'];

        $result = $this->setMessages('Customer updated successfully', false, \Constants::SUCCESS_CODE, $object);

        return $result;
    }
}
