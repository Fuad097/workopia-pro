<?php

namespace Framework;

use App\controllers\Errorcontroller;
use Framework\middleware\Authorize;

class Router
{
    protected $routes = [];

    /** 
     * @param string $uri
     * @param string $method
     * @param string $action
     * @param string $middleware
     * @return void
     */

    public function registerRoute($uri, $method,  $action,$middleware=[])
    {
        list($controller, $controllerMethod) = explode('@', $action);

        $this->routes[] = [
            'uri' => $uri,
            'method' => $method,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
            'middleware'=> $middleware
        ];
    }

    /** Add GET route
     * @param string $uri
     * @param string $method
     * @param string $middleware
     * @return void
     */

    public function get($uri, $controller,$middleware =[])
    {
        $this->registerRoute($uri, 'GET', $controller,$middleware);
    }
    /** Add a POST route
     * @param string $uri
     * @param string $method
     * @param string $middleware
     * @return void
     */

    public function post($uri,  $controller,$middleware = [])
    {
        $this->registerRoute($uri, 'POST', $controller,$middleware);
    }
    /** Add PUT route
     * @param string $uri
     * @param string $method
     * @param string $middleware
     * @return void
     */

    public function put($uri, $controller,$middleware=[])
    {
        $this->registerRoute($uri, 'PUT', $controller,$middleware);
    }
    /** Add Delete route
     * @param string $uri
     * @param string $method
     * @param string $middleware
     * @return void
     */

    public function delete($uri,  $controller,$middleware=[])
    {
        $this->registerRoute($uri, 'DELETE', $controller,$middleware);
    }

    /**
     * @param string $uri
     * @param string $method
     * @return void
     */

    public function route($uri)
    {

        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $requestMethod = strtoupper($_POST['_method']); // "DELETE", "PUT", etc.
        }
        foreach ($this->routes as $route) {
            $uriSegments = explode('/', trim($uri, '/'));

            $routeSegments = explode('/', trim($route['uri'], '/'));

            $match = true;


            if (count($uriSegments) === count($routeSegments) && strtoupper($route['method']) === $requestMethod) {

                $params = [];

                for ($i = 0; $i < count($uriSegments); $i++) {
                    if ($routeSegments[$i]  !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeSegments[$i])) {
                        $match = false;
                        break;
                    }

                    if (preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
                        $params[$matches[1]] = $uriSegments[$i];
                    }
                }
                if ($match) {
                    foreach($route['middleware'] as $middleware){
                        $ath = new Authorize();
                        $ath->handle($middleware);

                    }
                    $controller = 'App\\controllers\\' . $route['controller'];
                    $controllerMethod = $route['controllerMethod'];


                    $controllerInstance = new $controller();
                    $controllerInstance->$controllerMethod($params);
                    return;
                }
            }
           
        }

        Errorcontroller::notFound();
    }
}
