<?php

$router->group(['prefix' => 'v1', 'namespace' => '\App\Modules\Api\V1\Controllers'], function($router) {

    $router->post('signup', [
        'as' => 'signup',
        'uses' => 'UserController@signup'
    ]);

    $router->group(['middleware' => ['auth:sanctum']], function ($router) {

        $router->post('repayment', [
            'as' => 'loan_repayment',
            'uses' => 'RepaymentController@repayment'
        ]);

        $router->put('approve', [
            'as' => 'loan_approve',
            'uses' => 'LoanController@approve'
        ]);

        $router->post('loan-request', [
            'as' => 'loan_request',
            'uses' => 'LoanController@store'
        ]);
    });
});
