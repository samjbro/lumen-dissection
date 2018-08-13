<?php

namespace Laravel\Lumen\Routing;

class Router
{
    protected $routes =  [];
    public $namedRoutes =  [];

    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
        return $this;
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
        return $this;
    }

    public function addRoute($method, $uri, $action)
    {
        $action = $this->parseAction($action);
        if (! is_array($method)) {
            $method = [$method];
        }

        if (isset($action['as'])) {
            $this->namedRoutes[$action['as']] = $uri;
        }

        foreach($method as $verb) {
            $this->routes[$verb.$uri] = compact('method', 'uri', 'action');
        }
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

        if (isset($action['middleware']) && is_string($action['middleware'])) {
            $action['middleware'] = explode('|', $action['middleware']);
        }

        return $action;
    }
}