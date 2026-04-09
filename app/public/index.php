<?php

/**
 * This is the central route handler of the application.
 * It uses FastRoute to map URLs to controller methods.
 * 
 * See the documentation for FastRoute for more information: https://github.com/nikic/FastRoute
 */

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

// Load environment variables from app/.env
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// If it's an OPTIONS preflight request, stop further execution
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

/**
 * Define the routes for the application.
 */
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET',  '/login',    ['App\Controllers\LoginController', 'showLogin']);
    $r->addRoute('POST', '/login',    ['App\Controllers\LoginController', 'login']);
    $r->addRoute('POST', '/logout',   ['App\Controllers\LoginController', 'logout']);
    $r->addRoute('GET',  '/createaccount', ['App\Controllers\LoginController', 'showCreateAccount']);
    $r->addRoute('POST', '/createaccount', ['App\Controllers\LoginController', 'createAccount']);
    $r->addRoute('GET',  '/forgotpassword', ['App\Controllers\ResetPasswordController', 'showForgotPassword']);
    $r->addRoute('POST', '/forgotpassword', ['App\Controllers\ResetPasswordController', 'forgotPassword']);
    $r->addRoute('GET',  '/resetpassword/{token}', ['App\Controllers\ResetPasswordController', 'showResetPassword']);
    $r->addRoute('POST', '/resetpassword', ['App\Controllers\ResetPasswordController', 'resetPassword']);
    $r->addRoute('GET', '/', ['App\Controllers\ItemController', 'showAllItems']);
    $r->addRoute('GET', '/createitem', ['App\Controllers\ItemController', 'showCreateItem']);
    $r->addRoute('POST', '/createitem', ['App\Controllers\ItemController', 'createItem']);
    $r->addRoute('GET', '/myitems', ['App\Controllers\ItemController', 'showMyItems']);
    $r->addRoute('GET', '/edititem/{id}', ['App\Controllers\ItemController', 'showEditItem']);
    $r->addRoute('POST', '/edititem/{id}', ['App\Controllers\ItemController', 'updateItem']);
    $r->addRoute('POST', '/deleteitem/{id}', ['App\Controllers\ItemController', 'deleteItem']);
    $r->addRoute('GET', '/admin', ['App\Controllers\AdminController', 'showDashboard']);
    $r->addRoute('GET', '/admin/createaccount', ['App\Controllers\AdminController', 'showCreateUser']);
    $r->addRoute('POST', '/admin/createaccount', ['App\Controllers\AdminController', 'createUser']);
    $r->addRoute('POST', '/admin/user/role/{id}', ['App\Controllers\AdminController', 'updateUserRole']);
    $r->addRoute('POST', '/admin/user/delete/{id}', ['App\Controllers\AdminController', 'deleteUser']);
    $r->addRoute('GET', '/admin/edititem/{id}', ['App\Controllers\AdminController', 'showEditItem']);
    $r->addRoute('POST', '/admin/edititem/{id}', ['App\Controllers\AdminController', 'updateItem']);
    $r->addRoute('POST', '/admin/deleteitem/{id}', ['App\Controllers\AdminController', 'deleteItem']);
    $r->addRoute('GET', '/user', ['App\Controllers\UserController', 'showDashboard']);
    $r->addRoute('GET', '/mymessages', ['App\Controllers\UserController', 'showInbox']);
    $r->addRoute('GET', '/messages/{itemId}/{userId}', ['App\Controllers\UserController', 'showMessages']);
    $r->addRoute('POST', '/sendmessage', ['App\Controllers\UserController', 'sendMessage']);
    $r->addRoute('GET', '/privacy', ['App\Controllers\UserController', 'showPrivacyPolicy']);
    // API routes
    $r->addRoute('GET', '/api/messages/{itemId}/{userId}', ['App\Controllers\UserController', 'getMessagesApi']);
    $r->addRoute('POST', '/api/sendmessage', ['App\Controllers\UserController', 'sendMessageApi']);
});

/**
 * Get the request method and URI from the server variables and invoke the dispatcher.
 */
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

/**
 * Switch on the dispatcher result and call the appropriate controller method if found.
 */
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Not Found';
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;

    case FastRoute\Dispatcher::FOUND:
        $class = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $controller = new $class();
        $params = $routeInfo[2];

        if (!empty($params)) {
            $controller->$method($params);
        } else {
            $controller->$method();
        }
        break;
}