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

/* File Manager */

$routes->get('/file/(:segment)', 'Filemanager::views/$1');
$routes->get('/download/(:segment)', 'Filemanager::download/$1');

$routes->get('/'.$ROUTE_DASHBOARD.'/file-manager', 'Filemanager::index');
$routes->get('/'.$ROUTE_DASHBOARD.'/file-manager/folder/(:alphanum)', 'Filemanager::folder/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/file-manager/order/(:alphanum)', 'Filemanager::order/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/file-manager/recents', 'Filemanager::recents');
$routes->get('/'.$ROUTE_DASHBOARD.'/file-manager/recycle', 'Filemanager::recycle');
/*$routes->get('/'.$ROUTE_DASHBOARD.'/role/new', 'Roles::new');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/view/(:alphanum)', 'Roles::details/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/edit/(:alphanum)', 'Roles::edit/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/role/trash/(:alphanum)', 'Roles::trash/$1');*/

$routes->post('/'.$ROUTE_DASHBOARD.'/file-manager/find', 'Filemanager::find');
$routes->post('/'.$ROUTE_DASHBOARD.'/file-manager/content', 'Filemanager::content');
$routes->post('/'.$ROUTE_DASHBOARD.'/file-manager/folder/saved', 'Filemanager::folderSaved');
$routes->post('/'.$ROUTE_DASHBOARD.'/file-manager/upload', 'Filemanager::upload');
$routes->post('/'.$ROUTE_DASHBOARD.'/file-manager/saved', 'Filemanager::save');

$routes->post('/'.$ROUTE_DASHBOARD.'/file-manager/trash', 'Filemanager::trash');
$routes->post('/'.$ROUTE_DASHBOARD.'/file-manager/restore', 'Filemanager::restore');
$routes->post('/'.$ROUTE_DASHBOARD.'/file-manager/delete', 'Filemanager::delete');

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
$routes->get('/'.$ROUTE_SETTINGS.'/menu/orders', 'Menu::orders');
$routes->post('/'.$ROUTE_SETTINGS.'/menu/saved', 'Menu::saved');
$routes->post('/'.$ROUTE_SETTINGS.'/menu/orders/saved', 'Settings::save');
$routes->post('/'.$ROUTE_SETTINGS.'/menu/trash/(:alphanum)', 'Menu::trash/$1');

/* TAXONOMÍAS */
$routes->get('/'.$ROUTE_DASHBOARD.'/taxonomys', 'Taxonomy::index');
$routes->get('/'.$ROUTE_DASHBOARD.'/taxonomy/new', 'Taxonomy::new');
$routes->get('/'.$ROUTE_DASHBOARD.'/taxonomy/view/(:alphanum)', 'Taxonomy::details/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/taxonomy/edit/(:alphanum)', 'Taxonomy::edit/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/taxonomy/trash/(:alphanum)', 'Taxonomy::trash/$1');

$routes->post('/'.$ROUTE_DASHBOARD.'/taxonomy/saved', 'Taxonomy::saved');
$routes->post('/'.$ROUTE_DASHBOARD.'/taxonomy/move', 'Taxonomy::move');

/*FIELDS*/
$routes->get('/'.$ROUTE_DASHBOARD.'/fields/(:alphanum)', 'Fields::index/$1');
$routes->post('/'.$ROUTE_DASHBOARD.'/field/saved', 'Fields::saved');
$routes->post('/'.$ROUTE_DASHBOARD.'/field/trash/(:alphanum)', 'Fields::trash/$1');

/* TERMS */
$routes->get('/'.$ROUTE_DASHBOARD.'/terms/(:alphanum)', 'Terms::index/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/term/(:alphanum)/new', 'Terms::new/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/term/(:alphanum)/new/(:alphanum)', 'Terms::new/$1/$2');
$routes->get('/'.$ROUTE_DASHBOARD.'/term/(:alphanum)/view/(:alphanum)', 'Terms::details/$1/$2');
$routes->get('/'.$ROUTE_DASHBOARD.'/term/(:alphanum)/edit/(:alphanum)', 'Terms::edit/$1/$2');
$routes->get('/'.$ROUTE_DASHBOARD.'/term/(:alphanum)/trash/(:alphanum)', 'Terms::trash/$1/$2');

$routes->post('/'.$ROUTE_DASHBOARD.'/term/saved', 'Terms::saved');

/* ENTITYS */
$routes->get('/'.$ROUTE_DASHBOARD.'/entitys/(:alphanum)', 'Entitys::index/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/entity/(:alphanum)/new', 'Entitys::new/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/entity/(:alphanum)/new/(:alphanum)', 'Entitys::new/$1/$2');
$routes->get('/'.$ROUTE_DASHBOARD.'/entity/(:alphanum)/view/(:alphanum)', 'Entitys::details/$1/$2');
$routes->get('/'.$ROUTE_DASHBOARD.'/entity/(:alphanum)/edit/(:alphanum)', 'Entitys::edit/$1/$2');
$routes->get('/'.$ROUTE_DASHBOARD.'/entity/(:alphanum)/trash/(:alphanum)', 'Entitys::trash/$1/$2');

$routes->post('/'.$ROUTE_DASHBOARD.'/entity/saved', 'Entitys::saved');

/* VIEWS */
$routes->get('/'.$ROUTE_DASHBOARD.'/views', 'Views::index');
$routes->get('/'.$ROUTE_DASHBOARD.'/view/new', 'Views::new');
$routes->get('/'.$ROUTE_DASHBOARD.'/view/view/(:alphanum)', 'Views::details/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/view/(:segment)', 'Views::detail/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/view/(:segment)/(:segment)', 'Views::detail/$1/$2');

$routes->get('/'.$ROUTE_DASHBOARD.'/view/edit/(:alphanum)', 'Views::edit/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/view/trash/(:alphanum)', 'Views::trash/$1');

$routes->post('/'.$ROUTE_DASHBOARD.'/view/saved', 'Views::saved');

/* TABVIEWS */
$routes->get('/'.$ROUTE_DASHBOARD.'/tabviews/(:alphanum)', 'Tabviews::index/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/tabview/new/(:alphanum)', 'Tabviews::new/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/tabview/view/(:alphanum)', 'Tabviews::details/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/tabview/edit/(:alphanum)', 'Tabviews::edit/$1');
$routes->get('/'.$ROUTE_DASHBOARD.'/tabview/trash/(:alphanum)', 'Tabviews::trash/$1');

$routes->post('/'.$ROUTE_DASHBOARD.'/tabview/saved', 'Tabviews::saved');

//ahora hay que crear los fields de taxonomias con la estructura de menu
//adicional hay que crear terms y entitys sus respectivas tablas metas