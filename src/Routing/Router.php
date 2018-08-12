<?php

namespace Laravel\Lumen\Routing;

class Router
{
    public $app;
    protected $routes = [];

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
        return $this;
    }

    public function addRoute($method, $uri, $action)
    {
        $action = $this->parseAction($action);

        $this->routes[$method.$uri] = ['method' => $method, 'uri' => $uri, 'action' => $action];
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    protected function parseAction($action)
    {
        if (! is_array($action)) {
            return [$action];
        }
    }
}