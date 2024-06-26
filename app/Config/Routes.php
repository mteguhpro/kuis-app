<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');



$routes->addRedirect('/', '/play');
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

$routes->group('admin', ['filter' => 'aksesFilter:administrator'], static function ($routes) {
    $routes->resource('master-soal', [
        'websafe' => 1,
        'controller' => 'Kuis\MasterSoal',
        'except' => 'new',
    ]);

    $routes->resource('master-jawaban', [
        'websafe' => 1,
        'controller' => 'Kuis\Jawaban',
        'only' => 'create,delete',
    ]);
    $routes->get('list-data-opsi/(:num)', 'Kuis\Jawaban::listData/$1');
    $routes->post('tandai-jawaban-benar/(:num)', 'Kuis\Jawaban::tandaiBenar/$1');  
});

$routes->get('/play', 'Kuis\Exam::play'); //form-login
$routes->post('/play/soal', 'Kuis\Exam::soal');
$routes->post('/play/hasil', 'Kuis\Exam::hasil');
$routes->get('/play/list-id-soal', 'Kuis\Exam::listIdSoal');

