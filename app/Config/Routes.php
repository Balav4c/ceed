<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('admin/dashboard', 'admin\Dashboard::index');
$routes->get('admin', 'admin\Login::index');
$routes->post('admin/login', 'admin\Login::login');
$routes->get('admin/logout', 'admin\Login::logout');






$routes->get('admin/manage_user' , 'admin\User::index');
$routes->get('admin/adduser', 'admin\User::addUser'); 


// Save user (AJAX POST)
// $routes->post('admin/manage_user/save', 'admin\Manage_User::createUser');
$routes->post('admin/save/user', 'admin\User::saveUser');

$routes->get('admin/manage_role' , 'admin\Manage_Role::index');
$routes->get('admin/rolelist' , 'admin\Manage_Role::rolelist');