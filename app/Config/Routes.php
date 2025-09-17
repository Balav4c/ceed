<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


//website Routes
$routes->get('/', 'Home::index');
$routes->get('login','Login::index');













$routes->get('admin/dashboard', 'admin\Dashboard::index');
$routes->get('admin', 'admin\Login::index');
$routes->post('admin/login', 'admin\Login::login');
$routes->get('admin/logout', 'admin\Login::logout');






$routes->get('admin/manage_user' , 'admin\User::index');
$routes->get('admin/adduser', 'admin\User::addUser'); 
$routes->post('admin/manage_user/userlistajax', 'admin\User::userlistajax');
$routes->get('admin/adduser/edit/(:num)', 'admin\User::edit/$1');
$routes->post('admin/manage_user/delete', 'admin\User::deleteUser');
$routes->post('admin/manage_user/toggleStatus', 'admin\User::toggleStatus');
$routes->post('admin/save/user', 'admin\User::saveUser');


// Manage Role Routes
$routes->get('admin/manage_role' , 'admin\ManageRole::index');
$routes->get('admin/add_role' , 'admin\ManageRole::addrole');
$routes->post('admin/manage_role/store', 'admin\ManageRole::store');
$routes->post('admin/manage_role/rolelistajax', 'admin\ManageRole::rolelistajax');
$routes->get('admin/add_role/edit/(:num)', 'admin\ManageRole::edit/$1');
$routes->post('admin/manage_role/update/(:num)', 'admin\ManageRole::update/$1');
$routes->post('admin/manage_role/delete', 'admin\ManageRole::delete');
$routes->post('admin/manage_role/toggleStatus', 'admin\ManageRole::toggleStatus');



// Manage Course Routes
$routes->get('admin/manage_course', 'admin\Course::index'); // List page
$routes->get('admin/add_course', 'admin\Course::addCourse'); // Add form
$routes->get('admin/add_course/edit/(:num)', 'admin\Course::form/$1'); // Edit form
$routes->post('admin/manage_course/store', 'admin\Course::store'); // Save new
$routes->post('admin/manage_course/update/(:num)', 'admin\Course::update/$1'); // Update
$routes->post('admin/manage_course/courselistajax', 'admin\Course::courseListAjax'); // DataTable ajax
$routes->post('admin/manage_course/delete', 'admin\Course::delete'); // Delete






