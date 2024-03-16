<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$ROUTE_DASHBOARD = 'dashboard';
$routes->get('/'.$ROUTE_DASHBOARD.'/roles', 'Roles::index');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/new', 'Roles::new');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/view/(:alphanum)', 'Roles::details/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/edit/(:alphanum)', 'Roles::edit/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/trash/(:alphanum)', 'Roles::trash/$1');

$routes->post('/'.$ROUTE_DASHBOARD.'/role/saved', 'Roles::saved');
