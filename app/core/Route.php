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


    public function get($urlRoute, $option = [])
    {
        $this->routes['GET'][$urlRoute] = $option;
    }

    public function post($urlRoute, $option = [])
    {
        $this->routes['POST'][$urlRoute] = $option;
    }

    public function run()
    {
        $url = $this->parseURL();
        $currentPath = !empty($url) ? '/' . implode('/', $url) : '/';

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $matched = false;
        if (isset($this->routes[$requestMethod])) {
            foreach ($this->routes[$requestMethod] as $routeUrl => $option) {
                $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $routeUrl);

                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $currentPath, $matches)) {
                    $matched = true;
                    $controllerFull = $option[0];
                    $method = $option[1];

                    $controllerParts = explode('\\', $controllerFull);
                    $controllerName = end($controllerParts); 

                    if (file_exists('../app/controllers/' . $controllerName . '.php')) {
                        require_once '../app/controllers/' . $controllerName . '.php';
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
