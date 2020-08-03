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
    $router->get('videos/convertM3U8', 'VideoController@convertM3U8');
    $router->resource('videos', VideoController::class);
    $router->resource('tags', TagController::class);
    $router->resource('domains', DomainController::class);
    
});
