<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = 'WebEd\Plugins\Ecommerce\Addons\Customers';

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
        /**
         * Register to dashboard menu
         */
        \DashboardMenu::registerItem([
            'id' => 'webed-ecommerce-customers',
            'priority' => 4.7,
            'parent_id' => 'webed-ecommerce',
            'heading' => null,
            'title' => 'Customers',
            'font_icon' => 'icon-user',
            'link' => route('admin::ecommerce.customers.index.get'),
            'css_class' => null,
            'permissions' => [],
        ]);
    }
}
