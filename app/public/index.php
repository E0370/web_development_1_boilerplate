<?php

/**
 * This is the central route handler of the application.
 * It uses FastRoute to map URLs to controller methods.
 * 
 * See the documentation for FastRoute for more information: https://github.com/nikic/FastRoute
 */

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

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
    'lifetime' => 0,          // until browser closes
    'path'     => '/',
    'domain'   => '',         // current domain
    'secure'   => $secure,    // HTTPS only
    'httponly' => true,       // JS cannot access cookie
    'samesite' => 'Lax',      // protects against CSRF
]);

session_start();

/**
 * Define the routes for the application.
 */
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    //$r->addRoute('GET', '/', ['App\Controllers\HomePageController', 'showhome']);
    $r->addRoute('GET',  '/login',    ['App\Controllers\LoginController', 'showLogin']);
    $r->addRoute('POST', '/login',    ['App\Controllers\LoginController', 'login']);
    $r->addRoute('POST', '/logout',   ['App\Controllers\LoginController', 'logout']);
    $r->addRoute('GET',  '/create-account', ['App\Controllers\LoginController', 'showCreateAccount']);
    $r->addRoute('POST', '/create-account', ['App\Controllers\LoginController', 'createAccount']);
    $r->addRoute('GET',  '/forgot-password', ['App\Controllers\ResetPasswordController', 'showForgotPassword']);
    $r->addRoute('POST', '/forgot-password', ['App\Controllers\ResetPasswordController', 'forgotPassword']);
    $r->addRoute('GET',  '/reset-password/{token}', ['App\Controllers\ResetPasswordController', 'showResetPassword']);
    $r->addRoute('POST', '/reset-password', ['App\Controllers\ResetPasswordController', 'resetPassword']);
    $r->addRoute('GET', '/', ['App\Controllers\ItemController', 'showAllItems']);
    $r->addRoute('GET', '/createitem', ['App\Controllers\ItemController', 'showCreateItem']);
    $r->addRoute('POST', '/createitem', ['App\Controllers\ItemController', 'createItem']);
    $r->addRoute('GET', '/myitems', ['App\Controllers\ItemController', 'showMyItems']);
    $r->addRoute('GET', '/edititem/{id}', ['App\Controllers\ItemController', 'showEditItem']);
    $r->addRoute('POST', '/edititem/{id}', ['App\Controllers\ItemController', 'updateItem']);
    $r->addRoute('POST', '/deleteitem/{id}', ['App\Controllers\ItemController', 'deleteItem']);

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
    // Handle not found routes
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Not Found';
        break;
    // Handle routes that were invoked with the wrong HTTP method
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;
    // Handle found routes
    case FastRoute\Dispatcher::FOUND:
        /**
         * $routeInfo contains the data about the matched route.
         * 
         * $routeInfo[1] is the whatever we define as the third argument the `$r->addRoute` method.
         *  For instance for: `$r->addRoute('GET', '/hello/{name}', ['App\Controllers\HelloController', 'greet']);`
         *  $routeInfo[1] will be `['App\Controllers\HelloController', 'greet']`
         * 
         * Hint: we can use class strings like `App\Controllers\HelloController` to create new instances of that class.
         * Hint: in PHP we can use a string to call a class method dynamically, like this: `$instance->$methodName($args);`
         */

        // TODO: invoke the controller and method using the data in $routeInfo[1]

        /**
         * $route[2] contains any dynamic parameters parsed from the URL.
         * For instance, if we add a route like:
         *  $r->addRoute('GET', '/hello/{name}', ['App\Controllers\HelloController', 'greet']);
         * and the URL is `/hello/dan-the-man`, then `$routeInfo[2][name]` will be `dan-the-man`.
         */

        // TODO: pass the dynamic route data to the controller method
        // When done, visiting `http://localhost/hello/dan-the-man` should output "Hi, dan-the-man!"
        $class = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $controller = new $class();
        $params = $routeInfo[2];
        $controller->$method($params);

        break;
}
