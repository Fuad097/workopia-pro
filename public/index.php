<?php

require __DIR__ . '/../vendor/autoload.php';

use Framework\Session;
use Framework\Router;

Session::start();


require '../helpers.php';




// strip query string + trailing slash; map empty to '/'
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
$uri = $uri === '' ? '/' : $uri;




$router = new Router();

require basePath('routes.php');

$router->route($uri);
