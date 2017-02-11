<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Providers;

use Illuminate\Support\ServiceProvider;

class UninstallModuleServiceProvider extends ServiceProvider
{
    protected $module = 'WebEd\Plugins\Ecommerce\Addons\Customers';

    protected $moduleAlias = 'webed-ecommerce-customers';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->booted(function () {
            $this->booted();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    private function booted()
    {
        acl_permission()
        ->unsetPermissionByModule($this->module);

        $this->dropSchema();
    }

    private function dropSchema()
    {
        \Schema::dropIfExists('customers');
        \Schema::dropIfExists('customer_password_resets');
    }
}
