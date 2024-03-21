<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$ROUTE_DASHBOARD = 'dashboard';
$ROUTE_SETTINGS = 'dashboard/settings';

/* ROLES */
$routes->get('/'.$ROUTE_DASHBOARD.'/roles', 'Roles::index');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/new', 'Roles::new');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/view/(:alphanum)', 'Roles::details/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/edit/(:alphanum)', 'Roles::edit/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/trash/(:alphanum)', 'Roles::trash/$1');

$routes->post('/'.$ROUTE_DASHBOARD.'/role/saved', 'Roles::saved');

/* PERMISOS */
$routes->get('/'.$ROUTE_DASHBOARD.'/permissions/(:alphanum)', 'Permissions::index/$1');
$routes->post('/'.$ROUTE_DASHBOARD.'/permission/saved', 'Permissions::saved');


/* USUARIOS */
$routes->get('/'.$ROUTE_DASHBOARD.'/accounts/(:alphanum)', 'Accounts::index/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/account/(:alphanum)/new', 'Accounts::new/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/account/view/(:alphanum)', 'Accounts::details/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/account/edit/(:alphanum)', 'Accounts::edit/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/account/trash/(:alphanum)', 'Accounts::trash/$1');

$routes->post('/'.$ROUTE_DASHBOARD.'/account/saved', 'Accounts::saved');

/*MENU*/
$routes->get('/'.$ROUTE_SETTINGS.'/menus', 'Menu::index');
$routes->post('/'.$ROUTE_SETTINGS.'/menu/saved', 'Menu::saved');
$routes->post('/'.$ROUTE_SETTINGS.'/menu/trash/(:alphanum)', 'Menu::trash/$1');
