<?php

namespace Laravel\Lumen\Routing;

class Router
{
    public $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function get($uri, $action)
    {
        return $this;
    }
}