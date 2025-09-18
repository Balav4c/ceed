<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


//website Routes
$routes->get('/', 'Home::index');
$routes->get('signin','Login::index');
$routes->get('signup','Login::signup');

$routes->post('auth/login', 'Login::login');
$routes->post('save/user','Login::saveUser');
$routes->get('logout', 'Login::logout');













$routes->get('admin/dashboard', 'admin\Dashboard::index');
$routes->get('user/dashboard', 'admin\UserDashboard::index');


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
$routes->get('admin/manage_course', 'admin\Course::index'); 
$routes->get('admin/add_course', 'admin\Course::addCourse'); 
$routes->get('admin/add_course/edit/(:num)', 'admin\Course::form/$1'); 
$routes->post('admin/manage_course/save', 'admin\Course::save'); 
$routes->post('admin/manage_course/update/(:num)', 'admin\Course::update/$1');
$routes->post('admin/manage_course/courselistajax', 'admin\Course::courseListAjax'); 
$routes->get('admin/manage_course/edit/(:num)', 'admin\Course::edit/$1');
$routes->post('admin/manage_course/update/(:num)', 'admin\Course::update/$1');
$routes->post('admin/manage_course/delete', 'admin\Course::delete');
$routes->post('admin/manage_course/toggleStatus', 'admin\Course::toggleStatus');

// Module Routes
// Open add_module with course_id
$routes->get('admin/add_module/(:num)', 'admin\CourseModule::index/$1');
$routes->get('admin/manage_module', 'admin\CourseModule::addModule');
$routes->post('admin/save_module', 'admin\CourseModule::save');






