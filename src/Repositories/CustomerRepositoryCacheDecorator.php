<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Repositories;

use WebEd\Base\Caching\Repositories\AbstractRepositoryCacheDecorator;

use WebEd\Base\Caching\Repositories\Cache\UseSoftDeletesCache;
use WebEd\Base\Core\Repositories\Contracts\UseSoftDeletesContract;
use WebEd\Plugins\Ecommerce\Addons\Customers\Repositories\Contracts\CustomerRepositoryContract;

class CustomerRepositoryCacheDecorator extends AbstractRepositoryCacheDecorator  implements CustomerRepositoryContract, UseSoftDeletesContract
{
    use UseSoftDeletesCache;

    /**
     * @param array $data
     * @return array
     */
    public function createCustomer(array $data)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args());
    }

    /**
     * @param $id
     * @param array $data
     * @return array
     */
    public function updateCustomer($id, array $data)
    {
        return $this->afterUpdate(__FUNCTION__, func_get_args());
    }
}
