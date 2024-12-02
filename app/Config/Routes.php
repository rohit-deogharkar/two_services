<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::newredirect');
$routes->get('/home', 'Home::getData');

$routes->get('/form', 'Home::getForm');
$routes->post('/postdata', 'Home::postData');

$routes->get('delete/(:segment)', 'Home::deleteData/$1');

$routes->get('/update/(:segment)', 'Home::datatoupdate/$1');
$routes->post('/updatedetails', 'Home::postupdate');

$routes->get('/register', 'Home::getregister');
$routes->post('/postregister', 'Home::registeration');

$routes->get('/login', 'Home::getlogin');
$routes->post('/postlogin', 'Home::postlogin');

$routes->get('/logout', 'Home::logout');
$routes->post('/postfilter', 'Home::getData');


// $routes->get('/throwData', 'Home::throwData');

$routes->get('/download', 'Home::download');

$routes->post('/upload-file', 'Home::uploadFile');

$routes->get('/deleteall', 'Home::deleteAll');