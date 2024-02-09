<?php

namespace App\Router;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function __construct()
    {
        $this->initRoutes();
    }

    public function dispatch(string $uri, string $method): void
    {
        $route = $this->findRoute($uri, $method);

        if (!$route) $this->notFound();

        [$controller, $action] = $route->getAction();
        $controller = new $controller();

        call_user_func([$controller, $action]);
    }

    private function notFound(): void
    {
        require_once '../../public/static/404-page/404-page.php';
        exit();
    }

    private function findRoute(string $uri, string $method): false|Route
    {
        foreach (array_reverse($this->routes[$method]) as $routeUri => $route) {
            if (preg_match($routeUri, $uri)) {
                return $route;
            }
        }

        return false;
    }

    private function initRoutes(): void
    {
        foreach ($this->getRoutes() as $route) {
            $this->routes[$route->getMethod()][$route->getUri()] = $route;
        }
    }

    private function getRoutes(): array
    {
        return require_once 'routes.php';
    }
}
