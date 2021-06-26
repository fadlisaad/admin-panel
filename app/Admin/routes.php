<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('agencies', AgencyController::class);
    $router->resource('services', ServiceController::class);
    $router->resource('transactions', TransactionController::class);
    $router->resource('payments', PaymentController::class);
});
