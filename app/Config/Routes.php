<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');



$routes->addRedirect('/', '/welcome');
//Standar simpel
$routes->get('/auth', 'Auth::index'); //form-login
$routes->post('/auth/create', 'Auth::create'); //login
$routes->get('/auth/logout', 'Auth::logout'); //logout
$routes->get('/welcome', 'Auth::welcome', ['filter' => 'aksesFilter']); //index

$routes->group('administrator', ['filter' => 'aksesFilter:root,administrator'], static function ($routes) {
    $routes->resource('master-user', [
        'websafe' => 1,
        'controller' => 'Administrator\MasterUser',
        'except' => 'new',
    ]);
    
    $routes->resource('master-group', [
        'websafe' => 1,
        'controller' => 'Administrator\MasterGroup',
        'except' => 'new,edit',
    ]);

    $routes->post('master-user-ubah-password', 'Administrator\MasterUser::ubahPassword');
});
