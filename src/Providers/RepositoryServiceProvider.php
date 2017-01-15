<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Providers;

use Illuminate\Support\ServiceProvider;
use WebEd\Plugins\Ecommerce\Addons\Customers\Models\Customer;
use WebEd\Plugins\Ecommerce\Addons\Customers\Repositories\Contracts\CustomerRepositoryContract;
use WebEd\Plugins\Ecommerce\Addons\Customers\Repositories\CustomerRepository;
use WebEd\Plugins\Ecommerce\Addons\Customers\Repositories\CustomerRepositoryCacheDecorator;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $module = 'WebEd\Plugins\Ecommerce\Addons\Customers';

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CustomerRepositoryContract::class, function () {
            $repository = new CustomerRepository(new Customer());

            if (config('webed-caching.repository.enabled')) {
                return new CustomerRepositoryCacheDecorator($repository);
            }

            return $repository;
        });
    }
}
