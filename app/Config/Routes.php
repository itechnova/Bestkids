<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$ROUTE_DASHBOARD = 'dashboard';
$ROUTE_SETTINGS = 'dashboard/settings';

/* DASHBOARD */
$routes->get('/'.$ROUTE_DASHBOARD, 'Taxonomy::index');

/* AUTHENTICATE */
$routes->get('/login', 'Accounts::login');
$routes->post('/login', 'Accounts::login');

$routes->get('/lost', 'Accounts::lost');
$routes->get('/register', 'Accounts::register');

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

/* TAXONOMÍAS */
$routes->get('/'.$ROUTE_DASHBOARD.'/taxonomys', 'Taxonomy::index');
$routes->get('/'.$ROUTE_DASHBOARD.'/taxonomy/new', 'Taxonomy::new');
$routes->get('/'.$ROUTE_DASHBOARD.'/taxonomy/view/(:alphanum)', 'Taxonomy::details/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/taxonomy/edit/(:alphanum)', 'Taxonomy::edit/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/taxonomy/trash/(:alphanum)', 'Taxonomy::trash/$1');

$routes->post('/'.$ROUTE_DASHBOARD.'/taxonomy/saved', 'Taxonomy::saved');

/*FIELDS*/
$routes->get('/'.$ROUTE_DASHBOARD.'/fields/(:alphanum)', 'Fields::index/$1');
$routes->post('/'.$ROUTE_DASHBOARD.'/field/saved', 'Fields::saved');
$routes->post('/'.$ROUTE_DASHBOARD.'/field/trash/(:alphanum)', 'Fields::trash/$1');

/* TERMS */
$routes->get('/'.$ROUTE_DASHBOARD.'/terms/(:alphanum)', 'Terms::index/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/term/(:alphanum)/new', 'Terms::new/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/term/(:alphanum)/view/(:alphanum)', 'Terms::details/$1/$2');
$routes->get('/'.$ROUTE_DASHBOARD.'/term/(:alphanum)/edit/(:alphanum)', 'Terms::edit/$1/$2');
$routes->get('/'.$ROUTE_DASHBOARD.'/term/(:alphanum)/trash/(:alphanum)', 'Terms::trash/$1/$2');

$routes->post('/'.$ROUTE_DASHBOARD.'/term/saved', 'Terms::saved');

//ahora hay que crear los fields de taxonomias con la estructura de menu
//adicional hay que crear terms y entitys sus respectivas tablas metas