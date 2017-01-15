<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Http\Requests;

use WebEd\Base\Core\Http\Requests\Request;

class UpdateCustomerPasswordRequest extends Request
{
    public $rules = [
        'password' => 'required|max:60|confirmed|min:5|string'
    ];
}
