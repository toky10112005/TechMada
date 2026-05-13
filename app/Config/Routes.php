<?php
use CodeIgniter\Router\RouteCollection;

use App\Controllers\EtudiantController;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index'); 
// $routes->get('/', 'EtudiantController::index');
// $routes->get('/etudiant/(:num)','EtudiantController::show/$1');

$routes->get('/', 'Employe::index');
$routes->get('/user/login', 'User::index');
$routes->get('/redirectadmin', 'User::redirectadmin');
$routes->post('/user/login', 'User::login');
$routes->get('/user/db-test', 'User::dbTest');
$routes->get('/user/redirectinscription', 'User::redirectinscription');
$routes->post('/user/inscription', 'User::page');
$routes->post('/user/put', 'User::put');


$routes->get('/admin/login', 'Admin::loginPage');
$routes->post('/admin/login', 'Admin::loginAdmin');
$routes->get('/admin/insert', 'Admin::insertredirect');
$routes->post('/admin/put', 'Admin::put');


$routes->group('admin', ['filter' => 'role:admin'], function($routes) {
    // Autres routes réservées aux administrateurs
    });
