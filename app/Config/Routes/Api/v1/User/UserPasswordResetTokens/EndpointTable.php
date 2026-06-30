<?php
// Rotas REST para manipulação da tabela user_password_reset_tokens
// POST {{www}}/index.php/api/v1/user-password-reset-tokens/find?page=1&limit=20&sort=id&order=ASC
$routes->post('find', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::find');
// POST {{www}}/index.php/api/v1/user-password-reset-tokens/get-grouped?page=1&limit=20&sort=id&order=ASC
$routes->post('get-grouped', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::getGrouped');
// GET  {{www}}/index.php/api/v1/user-password-reset-tokens/search?q=termo&page=1&limit=20&sort=id&order=ASC
$routes->get('search', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::search');
// GET  {{www}}/index.php/api/v1/user-password-reset-tokens/get/{id}
$routes->get('get/(:num)', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::get/$1');
// GET  {{www}}/index.php/api/v1/user-password-reset-tokens/get-all?page=1&limit=20&sort=id&order=ASC
$routes->get('get-all', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::getAll');
// GET  {{www}}/index.php/api/v1/user-password-reset-tokens/get-no-pagination?sort=id&order=ASC
$routes->get('get-no-pagination', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::getNoPagination');
// GET  {{www}}/index.php/api/v1/user-password-reset-tokens/get-deleted/{id}
$routes->get('get-deleted/(:num)', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::getDeleted/$1');
// GET  {{www}}/index.php/api/v1/user-password-reset-tokens/get-with-deleted/{id}
$routes->get('get-with-deleted/(:num)', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::getWithDeleted/$1');
// GET  {{www}}/index.php/api/v1/user-password-reset-tokens/get-deleted-all?page=1&limit=20&sort=id&order=ASC
$routes->get('get-deleted-all', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::getDeletedAll');
// GET  {{www}}/index.php/api/v1/user-password-reset-tokens/get-all-with-deleted/{id}
$routes->get('get-all-with-deleted/(:num)', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::getAllWithDeleted/$1');
// GET  {{www}}/index.php/api/v1/user-password-reset-tokens/get-all-with-deleted?page=1&limit=20&sort=id&order=ASC
$routes->get('get-all-with-deleted', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::getAllWithDeleted');
// POST {{www}}/index.php/api/v1/user-password-reset-tokens/create
$routes->post('create', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::create');
// PUT  {{www}}/index.php/api/v1/user-password-reset-tokens/update/{id}
$routes->put('update/(:num)', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::update/$1');
// DELETE {{www}}/index.php/api/v1/user-password-reset-tokens/delete-soft/{id}
$routes->delete('delete-soft/(:num)', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::deleteSoft/$1');
// PATCH  {{www}}/index.php/api/v1/user-password-reset-tokens/delete-restore/{id}
$routes->patch('delete-restore/(:num)', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::deleteRestore/$1');
// DELETE {{www}}/index.php/api/v1/user-password-reset-tokens/delete-hard/{id}
$routes->delete('delete-hard/(:num)', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::deleteHard/$1');
// DELETE {{www}}/index.php/api/v1/user-password-reset-tokens/clear-deleted
$routes->delete('clear-deleted', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::clearDeleted');
// DELETE {{www}}/index.php/api/v1/user-password-reset-tokens/clear-deleted/{id}
$routes->delete('clear-deleted/(:num)', 'Api\V1\User\UserPasswordResetTokens\ResourceTableController::clearDeleted/$1');
