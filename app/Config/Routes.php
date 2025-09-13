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
$routes->post('admin/manage_user/userlistajax', 'admin\User::userlistajax');


$routes->post('admin/save/user', 'admin\User::saveUser');

$routes->get('admin/manage_role' , 'admin\Manage_Role::index');
$routes->get('admin/add_role' , 'admin\Manage_Role::addrole');
$routes->post('admin/manage_role/store', 'admin\Manage_Role::store');
$routes->post('admin/manage_role/rolelistajax', 'admin\Manage_Role::rolelistajax');
$routes->get('admin/add_role/edit/(:num)', 'admin\Manage_Role::edit/$1');
$routes->post('admin/manage_role/update/(:num)', 'admin\Manage_Role::update/$1');
$routes->post('admin/manage_role/delete', 'Admin\Manage_Role::delete');
$routes->post('admin/manage_role/toggleStatus', 'Admin\Manage_Role::toggleStatus');




