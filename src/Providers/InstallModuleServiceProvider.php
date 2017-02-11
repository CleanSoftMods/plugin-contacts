<?php namespace WebEd\Plugins\Ecommerce\Addons\Customers\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Illuminate\Database\Schema\Blueprint;

class InstallModuleServiceProvider extends ServiceProvider
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
        $this->createSchema();
        acl_permission()
            ->registerPermission('View customers', 'view-customers', $this->module)
            ->registerPermission('Create customers', 'create-customers', $this->module)
            ->registerPermission('Edit customers', 'edit-customers', $this->module)
            ->registerPermission('Delete customers', 'delete-customers', $this->module)
            ->registerPermission('Force delete customers', 'force-delete-customers', $this->module);
    }

    private function createSchema()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->increments('id');
            $table->string('username', 100)->unique();
            $table->string('email', 255);
            $table->string('password');
            $table->string('display_name', 150)->nullable();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('activation_code', 100)->nullable();
            $table->string('avatar', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('mobile_phone', 20)->nullable();
            $table->enum('sex', ['male', 'female', 'other'])->default('male');
            $table->enum('status', ['activated', 'disabled'])->default('activated');
            $table->dateTime('birthday')->nullable();
            $table->text('description')->nullable();
            $table->rememberToken();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('disabled_until')->nullable();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('customer_password_resets', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamps();
        });
    }
}
