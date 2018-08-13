<?php

namespace Laravel\Lumen;

use Illuminate\Container\Container;
use Laravel\Lumen\Concerns\RegistersExceptionHandlers;
use Laravel\Lumen\Concerns\RoutesRequests;
use Laravel\Lumen\Routing\Router;
use Laravel\Lumen\Routing\UrlGenerator;

class Application extends Container
{
    use RoutesRequests,
        RegistersExceptionHandlers;

    public $router;
    protected $ranServiceBinders = [];

    public function __construct()
    {
        $this->bootstrapContainer();
        $this->bootstrapRouter();
    }

    public function bootstrapContainer()
    {
        static::setInstance($this);

        $this->registerContainerAliases();
    }

    public function bootstrapRouter()
    {
        $this->router = new Router();
    }

    public function make($abstract, array $parameters = [])
    {
        $abstract = $this->getAlias($abstract);

        if (array_key_exists($abstract, $this->availableBindings) &&
            ! array_key_exists($this->availableBindings[$abstract], $this->ranServiceBinders)
        ) {
            $this->{$method = $this->availableBindings[$abstract]}();
            $this->ranServiceBinders[$method] = true;
        }

        return parent::make($abstract, $parameters);
    }

    protected function registerContainerAliases()
    {
        $this->aliases = [
          'request' => 'Illuminate\Http\Request'
        ];
    }

    protected function registerUrlGeneratorBindings()
    {
        $this->singleton('url', function () {
           return new UrlGenerator($this);
        });
    }

    public $availableBindings = [
        'url' => 'registerUrlGeneratorBindings',
    ];
}