<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Repositories;

use WebEd\Base\Caching\Services\Traits\Cacheable;
use WebEd\Base\Core\Repositories\Eloquent\EloquentBaseRepository;
use WebEd\Base\Caching\Services\Contracts\CacheableContract;

use WebEd\Plugins\Ecommerce\Addons\Customers\Repositories\Contracts\CustomerRepositoryContract;

class CustomerRepository extends EloquentBaseRepository implements CustomerRepositoryContract, CacheableContract
{
    use Cacheable;

    protected $rules = [
        'username' => 'required|between:3,100|string|unique:users|alpha_dash',
        'email' => 'required|between:5,255|email|unique:users',
        'password' => 'required',
        'status' => 'string|required|in:activated,disabled,deleted',
        'display_name' => 'string|between:1,150|nullable',
        'first_name' => 'string|between:1,100|required',
        'last_name' => 'string|between:1,100|required',
        'avatar' => 'string|nullable',
        'phone' => 'string|max:20|nullable',
        'mobile_phone' => 'string|max:20|nullable',
        'sex' => 'string|required|in:male,female,other',
        'birthday' => 'date_multi_format:Y-m-d H:i:s,Y-m-d|nullable',
        'description' => 'string|max:1000|nullable',
        'created_by' => 'integer|required|min:0',
        'updated_by' => 'integer|min:0|required',
        'last_login_at' => 'string|date_format:Y-m-d H:i:s|nullable',
        'last_activity_at' => 'string|date_format:Y-m-d H:i:s|nullable',
        'disabled_until' => 'string|date_format:Y-m-d H:i:s|nullable',
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
    ];

    /**
     * @param array $data
     * @return array
     */
    public function createCustomer(array $data)
    {
        $resultEditObject = $this->editWithValidate(0, $data, true, false);

        if ($resultEditObject['error']) {
            return response_with_messages($resultEditObject['messages'], true, \Constants::ERROR_CODE);
        }
        $object = $resultEditObject['data'];

        $result = response_with_messages('Customer created successfully', false, \Constants::SUCCESS_CODE, $object);

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
            return response_with_messages($resultEditObject['messages'], true, \Constants::ERROR_CODE);
        }
        $object = $resultEditObject['data'];

        $result = response_with_messages('Customer updated successfully', false, \Constants::SUCCESS_CODE, $object);

        return $result;
    }
}
