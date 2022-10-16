<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix'=>'/api/user'], function () use ($router){
    $router->post('register','UserController@register');
    $router->post('sign-in','UserController@signIn');
    $router->post('recovery-password','UserController@recoveryPassword');
    $router->patch('recovery-password','UserController@setNewPassword');
});

$router->group(['prefix'=>'/api/user','middleware'=>'auth'], function () use ($router){
    $router->get('companies','CompanyController@getCompanies');
    $router->post('companies','CompanyController@addCompany');
});
