<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Repositories\Contracts;

interface CustomerRepositoryContract
{
    /**
     * @param array $data
     * @return array
     */
    public function createCustomer(array $data);

    /**
     * @param $id
     * @param array $data
     * @return array
     */
    public function updateCustomer($id, array $data);
}
