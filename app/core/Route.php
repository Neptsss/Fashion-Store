<?php

namespace App\Core;

class Route
{
    protected $controller = "Home";
    protected $method = 'index';
    protected $param = [];
    protected $routes = [
        'GET' => [],
        'POST' => [],
    ];
    private $middlewareMap = [
        'auth' => [AuthMiddleware::class, 'auth'],
        'pembeli' => [AuthMiddleware::class, 'isPembeli'],
        'penjual' => [AuthMiddleware::class, 'isPenjual'],
    ];


    public function get($urlRoute, $option = [], $middleware = [])
    {
        $this->routes['GET'][$urlRoute] = [
            'action' => $option,
            'middleware' => $middleware
        ];
    }

    public function post($urlRoute, $option = [], $middleware = [])
    {
        $this->routes['POST'][$urlRoute] = [
            'action' => $option,
            'middleware' => $middleware
        ];
    }

    public function run()
    {
        $url = $this->parseURL();
        $currentPath = !empty($url) ? '/' . implode('/', $url) : '/';

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $matched = false;
        if (isset($this->routes[$requestMethod])) {
            foreach ($this->routes[$requestMethod] as $routeUrl => $route) {


                $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $routeUrl);

                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $currentPath, $matches)) {
                    $matched = true;
                    $controllerFull = $route['action'][0];
                    $method         = $route['action'][1];
                    $middlewares    = $route['middleware'];

                    $controllerParts = explode('\\', $controllerFull);
                    $controllerName = end($controllerParts);

                    if (file_exists('../app/controllers/' . $controllerName . '.php')) {
                        require_once '../app/controllers/' . $controllerName . '.php';

                        foreach ($middlewares as $middleware) {
                            if (isset($this->middlewareMap[$middleware])) {
                                call_user_func($this->middlewareMap[$middleware]);
                            }
                        }
                        $this->controller = new $controllerFull;

                        if (method_exists($this->controller, $method)) {
                            array_shift($matches);
                            $this->param = $matches;

                            call_user_func_array([$this->controller, $method], $this->param);
                        }
                    }
                    break;
                }
            }
            if (!$matched) {
                echo "404 - Halaman tidak Ditemukan";
            }
        }
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
