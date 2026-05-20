<?php
use CodeIgniter\Router\RouteCollection;

use App\Controllers\EtudiantController;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Employe::index');
$routes->get('/login', 'Employe::index');
$routes->post('/login', 'Employe::login');
$routes->post('/user/login', 'Employe::login');
$routes->get('/logout', 'Employe::logout');

// Congés
$routes->post('/conge/submit', 'Conge::submit');
$routes->get('/conges/my', 'Employe::index', ['filter' => 'role:user']);

// Routes protégées par rôle
$routes->get('/dashboard/admin', 'Employe::dashboardAdmin', ['filter' => 'role:admin']);
$routes->get('/dashboard/rh', 'Employe::dashboardRh', ['filter' => 'role:rh']);
$routes->get('/dashboard/user', 'Employe::dashboardUser', ['filter' => 'role:user']);
$routes->get('/dashboard/profil', 'Employe::profileUser', ['filter' => 'role:user']);

// Administration des employés
$routes->group('admin', ['filter' => 'role:admin'], function($routes){
    $routes->get('createEmploye', 'Employe::createEmploye');
    $routes->post('createEmploye', 'Employe::storeEmploye');
});

// Liste des employés (publique pour test)
$routes->get('/employes', 'Employe::index');

$routes->group('employes', ['filter' => 'role:user'], function($routes){
    $routes->get('nouvelledemande', 'Conge::create');
});

