<?php
// Rotas REST para manipulação da tabela user_user_data
// POST {{www}}/index.php/api/v1/user-user-data/find?page=1&limit=20&sort=id&order=ASC
$routes->post('find', 'Api\V1\User\UserUserData\ResourceTableController::find');
// POST {{www}}/index.php/api/v1/user-user-data/get-grouped?page=1&limit=20&sort=id&order=ASC
$routes->post('get-grouped', 'Api\V1\User\UserUserData\ResourceTableController::getGrouped');
// GET  {{www}}/index.php/api/v1/user-user-data/search?q=termo&page=1&limit=20&sort=id&order=ASC
$routes->get('search', 'Api\V1\User\UserUserData\ResourceTableController::search');
// GET  {{www}}/index.php/api/v1/user-user-data/get/{id}
$routes->get('get/(:num)', 'Api\V1\User\UserUserData\ResourceTableController::get/$1');
// GET  {{www}}/index.php/api/v1/user-user-data/get-all?page=1&limit=20&sort=id&order=ASC
$routes->get('get-all', 'Api\V1\User\UserUserData\ResourceTableController::getAll');
// GET  {{www}}/index.php/api/v1/user-user-data/get-no-pagination?sort=id&order=ASC
$routes->get('get-no-pagination', 'Api\V1\User\UserUserData\ResourceTableController::getNoPagination');
// GET  {{www}}/index.php/api/v1/user-user-data/get-deleted/{id}
$routes->get('get-deleted/(:num)', 'Api\V1\User\UserUserData\ResourceTableController::getDeleted/$1');
// GET  {{www}}/index.php/api/v1/user-user-data/get-with-deleted/{id}
$routes->get('get-with-deleted/(:num)', 'Api\V1\User\UserUserData\ResourceTableController::getWithDeleted/$1');
// GET  {{www}}/index.php/api/v1/user-user-data/get-deleted-all?page=1&limit=20&sort=id&order=ASC
$routes->get('get-deleted-all', 'Api\V1\User\UserUserData\ResourceTableController::getDeletedAll');
// GET  {{www}}/index.php/api/v1/user-user-data/get-all-with-deleted/{id}
$routes->get('get-all-with-deleted/(:num)', 'Api\V1\User\UserUserData\ResourceTableController::getAllWithDeleted/$1');
// GET  {{www}}/index.php/api/v1/user-user-data/get-all-with-deleted?page=1&limit=20&sort=id&order=ASC
$routes->get('get-all-with-deleted', 'Api\V1\User\UserUserData\ResourceTableController::getAllWithDeleted');
// POST {{www}}/index.php/api/v1/user-user-data/create
$routes->post('create', 'Api\V1\User\UserUserData\ResourceTableController::create');
// PUT  {{www}}/index.php/api/v1/user-user-data/update/{id}
$routes->put('update/(:num)', 'Api\V1\User\UserUserData\ResourceTableController::update/$1');
// DELETE {{www}}/index.php/api/v1/user-user-data/delete-soft/{id}
$routes->delete('delete-soft/(:num)', 'Api\V1\User\UserUserData\ResourceTableController::deleteSoft/$1');
// PATCH  {{www}}/index.php/api/v1/user-user-data/delete-restore/{id}
$routes->patch('delete-restore/(:num)', 'Api\V1\User\UserUserData\ResourceTableController::deleteRestore/$1');
// DELETE {{www}}/index.php/api/v1/user-user-data/delete-hard/{id}
$routes->delete('delete-hard/(:num)', 'Api\V1\User\UserUserData\ResourceTableController::deleteHard/$1');
// DELETE {{www}}/index.php/api/v1/user-user-data/clear-deleted
$routes->delete('clear-deleted', 'Api\V1\User\UserUserData\ResourceTableController::clearDeleted');
// DELETE {{www}}/index.php/api/v1/user-user-data/clear-deleted/{id}
$routes->delete('clear-deleted/(:num)', 'Api\V1\User\UserUserData\ResourceTableController::clearDeleted/$1');
