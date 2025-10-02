<?php 


require '../helpers.php';




// strip query string + trailing slash; map empty to '/'
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
$uri = $uri === '' ? '/' : $uri;

$method = $_SERVER['REQUEST_METHOD'];

require basePath('Router.php');

$router = new Router();

require basePath('routes.php');

$router->route($uri,$method);


