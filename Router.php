<?php

class Router
{
    protected $routes = [];

    public function registerRoute($uri, $method,  $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'method' => $method,
            'controller' => $controller,
        ];
    }

    /** Add GET route
     * @param string $uri
     * @param string $method
     * @return void
     */

    public function get($uri, $controller)
    {
        $this->registerRoute($uri, 'GET', $controller);
    }
    /** Add a POST route
     * @param string $uri
     * @param string $method
     * @return void
     */

    public function post($uri,  $controller)
    {
        $this->registerRoute($uri, 'POST', $controller);
    }
    /** Add PUT route
     * @param string $uri
     * @param string $method
     * @return void
     */

    public function put($uri, $controller)
    {
        $this->registerRoute($uri, 'PUT', $controller);
    }
    /** Add Delete route
     * @param string $uri
     * @param string $method
     * @return void
     */

    public function delete($uri,  $controller)
    {
        $this->registerRoute($uri, 'DELETE', $controller);
    }

    /**
     * @param string $uri
     * @param string $method
     * @return void
     */

    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {
                require basePath($route['controller']);
                return;
            }
        }

        http_response_code(404);
        loadView('error/404');
        exit;
    }
}
