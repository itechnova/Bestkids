<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$ROUTE_DASHBOARD = 'dashboard';
$routes->get('/'.$ROUTE_DASHBOARD.'/roles', 'Roles::index');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/new', 'Roles::new');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/edit', 'Roles::edit');
