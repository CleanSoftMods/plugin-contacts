<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Http\Requests;

use WebEd\Base\Core\Http\Requests\Request;

class CreateCustomerRequest extends Request
{
    protected $rules = [
        'username' => 'required|between:3,100|string|alpha_dash',
        'email' => 'required|between:5,255|email',
        'password' => 'string|required',
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
    ];
}
