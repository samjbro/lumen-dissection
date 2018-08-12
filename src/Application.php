<?php

namespace Laravel\Lumen;

use Illuminate\Container\Container;
use Laravel\Lumen\Routing\Router;


class Application extends Container
{
    use Concerns\RoutesRequests;
    use Concerns\RegistersExceptionHandlers;

    public $router;


    public function __construct($basePath = null)
    {
//        $this->bootstrapContainer();
        $this->bootstrapRouter();
    }

//    protected function bootstrapContainer()
//    {
//        $this->registerContainerAliases();
//    }

    protected function bootstrapRouter()
    {
        $this->router = new Router($this);
    }

//    public function make($abstract, array $parameters = [])
//    {
//        return parent::make($abstract, $parameters);
//    }
//
//    protected function registerContainerAliases()
//    {
//        $this->aliases = [
//            'request' => 'Illuminate\Http\Request',
//        ];
//    }

}