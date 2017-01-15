<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Http\Requests;

use WebEd\Base\Core\Http\Requests\Request;

class UpdateCustomerRequest extends Request
{
    public $rules = [

    ];

    public function authorize()
    {
        //return $this->user()->hasPermission('edit-page');
        return true;
    }
}
