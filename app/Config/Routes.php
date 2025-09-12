<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('admin/dashboard', 'admin\Dashboard::index');
$routes->get('admin', 'admin\Login::index');



$routes->get('admin/manage_user' , 'admin\Manage_User::index');
$routes->get('admin/adduser', 'admin\Manage_User::get'); 


$routes->get('admin/manage_role' , 'admin\Manage_Role::index');
$routes->get('admin/add_role' , 'admin\Manage_Role::addrole');
$routes->post('admin/manage_role/store', 'admin\Manage_Role::store');
$routes->post('admin/manage_role/rolelistajax', 'admin\Manage_Role::rolelistajax');

