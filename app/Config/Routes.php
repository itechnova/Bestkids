<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$ROUTE_DASHBOARD = 'dashboard';

/* ROLES */
$routes->get('/'.$ROUTE_DASHBOARD.'/roles', 'Roles::index');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/new', 'Roles::new');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/view/(:alphanum)', 'Roles::details/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/edit/(:alphanum)', 'Roles::edit/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/trash/(:alphanum)', 'Roles::trash/$1');

$routes->post('/'.$ROUTE_DASHBOARD.'/role/saved', 'Roles::saved');

/* PERMISOS */
$routes->get('/'.$ROUTE_DASHBOARD.'/permissions/(:alphanum)', 'Permissions::index/$1');
// $routes->get('/'.$ROUTE_DASHBOARD.'/permission/new', 'Permissions::new');
// $routes->get('/'.$ROUTE_DASHBOARD.'/permission/view/(:alphanum)', 'Permissions::details/$1');
// $routes->get('/'.$ROUTE_DASHBOARD.'/permission/edit/(:alphanum)', 'Permissions::edit/$1');
// $routes->get('/'.$ROUTE_DASHBOARD.'/permission/trash/(:alphanum)', 'Permissions::trash/$1');

$routes->post('/'.$ROUTE_DASHBOARD.'/permission/saved', 'Permissions::saved');
