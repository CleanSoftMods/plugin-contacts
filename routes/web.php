<?php
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/**
 * Admin routes
 */

$adminRoute = config('webed.admin_route');

$moduleRoute = 'ecommerce/customers';

Route::group(['prefix' => $adminRoute . '/' . $moduleRoute], function (Router $router) use ($adminRoute, $moduleRoute) {
    $router->get('/', 'CustomerController@getIndex')
        ->name('admin::ecommerce.customers.index.get')
        ->middleware('has-permission:view-customers');

    $router->post('/', 'CustomerController@postListing')
        ->name('admin::ecommerce.customers.index.post')
        ->middleware('has-permission:view-customers');

    $router->post('update-status/{id}/{status}', 'CustomerController@postUpdateStatus')
        ->name('admin::ecommerce.customers.update-status.post')
        ->middleware('has-permission:edit-customers');

    $router->post('restore/{id}', 'CustomerController@postRestore')
        ->name('admin::ecommerce.customers.restore.post')
        ->middleware('has-permission:edit-customers');

    $router->get('create', 'CustomerController@getCreate')
        ->name('admin::ecommerce.customers.create.get')
        ->middleware('has-permission:create-customers');

    $router->post('create', 'CustomerController@postCreate')
        ->name('admin::ecommerce.customers.create.post')
        ->middleware('has-permission:create-customers');

    $router->get('edit/{id}', 'CustomerController@getEdit')
        ->name('admin::ecommerce.customers.edit.get')
        ->middleware('has-permission:edit-customers');

    $router->post('edit/{id}', 'CustomerController@postEdit')
        ->name('admin::ecommerce.customers.edit.post')
        ->middleware('has-permission:edit-customers');

    $router->post('update-password/{id}', 'CustomerController@postUpdatePassword')
        ->name('admin::ecommerce.customers.update-password.post')
        ->middleware('has-permission:edit-customers');

    $router->delete('delete/{id}', 'CustomerController@deleteDelete')
        ->name('admin::ecommerce.customers.delete.delete')
        ->middleware('has-permission:delete-customers');
});
