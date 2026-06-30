<?php
// Rotas REST para manipulação da tabela user_users
// POST {{www}}/index.php/api/v1/user-users/find?page=1&limit=20&sort=id&order=ASC
$routes->post('find', 'Api\V1\User\UserUsers\ResourceTableController::find');
// POST {{www}}/index.php/api/v1/user-users/get-grouped?page=1&limit=20&sort=id&order=ASC
$routes->post('get-grouped', 'Api\V1\User\UserUsers\ResourceTableController::getGrouped');
// GET  {{www}}/index.php/api/v1/user-users/search?q=termo&page=1&limit=20&sort=id&order=ASC
$routes->get('search', 'Api\V1\User\UserUsers\ResourceTableController::search');
// GET  {{www}}/index.php/api/v1/user-users/get/{id}
$routes->get('get/(:num)', 'Api\V1\User\UserUsers\ResourceTableController::get/$1');
// GET  {{www}}/index.php/api/v1/user-users/get-all?page=1&limit=20&sort=id&order=ASC
$routes->get('get-all', 'Api\V1\User\UserUsers\ResourceTableController::getAll');
// GET  {{www}}/index.php/api/v1/user-users/get-no-pagination?sort=id&order=ASC
$routes->get('get-no-pagination', 'Api\V1\User\UserUsers\ResourceTableController::getNoPagination');
// GET  {{www}}/index.php/api/v1/user-users/get-deleted/{id}
$routes->get('get-deleted/(:num)', 'Api\V1\User\UserUsers\ResourceTableController::getDeleted/$1');
// GET  {{www}}/index.php/api/v1/user-users/get-with-deleted/{id}
$routes->get('get-with-deleted/(:num)', 'Api\V1\User\UserUsers\ResourceTableController::getWithDeleted/$1');
// GET  {{www}}/index.php/api/v1/user-users/get-deleted-all?page=1&limit=20&sort=id&order=ASC
$routes->get('get-deleted-all', 'Api\V1\User\UserUsers\ResourceTableController::getDeletedAll');
// GET  {{www}}/index.php/api/v1/user-users/get-all-with-deleted/{id}
$routes->get('get-all-with-deleted/(:num)', 'Api\V1\User\UserUsers\ResourceTableController::getAllWithDeleted/$1');
// GET  {{www}}/index.php/api/v1/user-users/get-all-with-deleted?page=1&limit=20&sort=id&order=ASC
$routes->get('get-all-with-deleted', 'Api\V1\User\UserUsers\ResourceTableController::getAllWithDeleted');
// POST {{www}}/index.php/api/v1/user-users/create
$routes->post('create', 'Api\V1\User\UserUsers\ResourceTableController::create');
// PUT  {{www}}/index.php/api/v1/user-users/update/{id}
$routes->put('update/(:num)', 'Api\V1\User\UserUsers\ResourceTableController::update/$1');
// DELETE {{www}}/index.php/api/v1/user-users/delete-soft/{id}
$routes->delete('delete-soft/(:num)', 'Api\V1\User\UserUsers\ResourceTableController::deleteSoft/$1');
// PATCH  {{www}}/index.php/api/v1/user-users/delete-restore/{id}
$routes->patch('delete-restore/(:num)', 'Api\V1\User\UserUsers\ResourceTableController::deleteRestore/$1');
// DELETE {{www}}/index.php/api/v1/user-users/delete-hard/{id}
$routes->delete('delete-hard/(:num)', 'Api\V1\User\UserUsers\ResourceTableController::deleteHard/$1');
// DELETE {{www}}/index.php/api/v1/user-users/clear-deleted
$routes->delete('clear-deleted', 'Api\V1\User\UserUsers\ResourceTableController::clearDeleted');
// DELETE {{www}}/index.php/api/v1/user-users/clear-deleted/{id}
$routes->delete('clear-deleted/(:num)', 'Api\V1\User\UserUsers\ResourceTableController::clearDeleted/$1');
