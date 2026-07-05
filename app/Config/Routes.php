<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->group('api/v1', function ($routes) {

    // =========================================================================
    // /User — Módulo de usuários e autenticação
    // =========================================================================

    $routes->group('auth', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/User/AuthUser/EndPointView.php';
    });

    $routes->group('user-password-resets', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/User/UserPasswordResets/EndpointTable.php';
    });

    $routes->group('user-saas-tenants', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/User/UserSaasTenants/EndpointTable.php';
    });

    $routes->group('user-tenants', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/User/UserTenants/EndpointTable.php';
    });

    $routes->group('user-tenants-view', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/User/UserTenants/EndPointView.php';
    });

    $routes->group('user-action-logs', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/User/UserActionLogs/EndpointTable.php';
    });

    $routes->group('user-password-reset-tokens', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/User/UserPasswordResetTokens/EndpointTable.php';
    });

    $routes->group('user-profiles', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/User/UserProfiles/EndpointTable.php';
    });

    $routes->group('user-users', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/User/UserUsers/EndpointTable.php';
    });

    $routes->group('user-user-data', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/User/UserUserData/EndpointTable.php';
    });

    $routes->group('user-refresh-tokens', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/User/UserRefreshTokens/EndpointTable.php';
    });

    // =========================================================================
    // /Eleicao — Módulo de dados eleitorais RJ
    // =========================================================================

    $routes->group('votacao-candidato-munzona-2022-rj-view', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/Eleicao/VotacaoCandidatoMunzona2022RJ/EndPointView.php';
    });

    $routes->group('municipio-rj', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/Eleicao/MunicipioRJ/EndpointTable.php';
    });

    $routes->group('municipio-rj-view', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/Eleicao/MunicipioRJ/EndPointView.php';
    });

    $routes->group('mandatario-rj', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/Eleicao/MandatarioRJ/EndpointTable.php';
    });

    $routes->group('mandatario-rj-view', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/Eleicao/MandatarioRJ/EndPointView.php';
    });

    $routes->group('candidato-2022-rj', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/Eleicao/Candidato2022RJ/EndpointTable.php';
    });

    $routes->group('candidato-2022-rj-view', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/Eleicao/Candidato2022RJ/EndPointView.php';
    });

    $routes->group('candidato-2024-rj', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/Eleicao/Candidato2024RJ/EndpointTable.php';
    });

    $routes->group('candidato-2024-rj-view', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/Eleicao/Candidato2024RJ/EndPointView.php';
    });

    $routes->group('municipio-ibge-tse', function ($routes) {
        require __DIR__ . '/Routes/Api/v1/Eleicao/MunicipioIbgeTse/EndpointTable.php';
    });
});
