<?php

namespace Laravel\Lumen;

use Laravel\Lumen\Routing\Router;

class Application
{
    use Concerns\RoutesRequests;

    public $router;

    public function __construct($basePath = null)
    {
        $this->bootstrapRouter();
    }

    protected function bootstrapRouter()
    {
        $this->router = new Router($this);
    }
}