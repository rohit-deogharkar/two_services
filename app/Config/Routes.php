<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::getData');
$routes->get('/form', 'Home::getForm');
$routes->post('/postdata', 'Home::postData');
$routes->get('delete/(:segment)', 'Home::deleteData/$1');
$routes->get('/update/(:segment)', 'Home::datatoupdate/$1');
$routes->post('/updatedetails/(:segment)', 'Home::postupdate/$1');
$routes->get('/register', 'Home::getregister');
$routes->post('/postregister', 'Home::registeration');