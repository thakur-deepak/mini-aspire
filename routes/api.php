<?php

$router->group(['prefix' => 'v1', 'namespace' => '\App\Modules\Api\V1\Controllers', 'middleware'=>'cors'], function($router) {

    // public api like

    $router->group(['middleware' => ['CheckAccessToken']], function ($router) {

        // API require token

        $router->group(['middleware' => ['IsAuthorized']], function ($router) {

            // API require token and authorization 

        });
    });
});