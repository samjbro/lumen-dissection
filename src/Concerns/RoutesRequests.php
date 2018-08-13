<?php

namespace Laravel\Lumen\Concerns;

use Closure;
use Exception;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;
use Laravel\Lumen\Routing\Pipeline;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait RoutesRequests
{
    protected $middleware = [];
    protected $routeMiddleware = [];

    public function middleware($middleware)
    {
        $this->middleware = array_unique(array_merge($this->middleware, $middleware));

        return $this;
    }

    public function routeMiddleware(array $middleware)
    {
        $this->routeMiddleware = array_merge($this->routeMiddleware, $middleware);

        return $this;
    }

    public function handle(SymfonyRequest $request)
    {
        $response = $this->dispatch($request);

        if (count($this->middleware > 0)) {
            $this->callTerminableMiddleware($response);
        }

        return $response;
    }

    public function dispatch($request = null)
    {
        $method = $request->getMethod();
        $pathInfo = $request->getPathInfo();

        try {
            return $this->sendThroughPipeline($this->middleware, function () use ($method, $pathInfo) {
                $routes = $this->router->getRoutes();

                if (isset($routes[$method.$pathInfo])) {
                    $action = $routes[$method.$pathInfo]['action'];
                    return $this->handleFoundRoute([true, $action, []]);
                }
            // Just use FastRoute\simpleDispatcher to parse route parameters
                return $this->handleDispatcherResponse(
                    $this->createDispatcher()->dispatch($method, $pathInfo)
                );
            });
        } catch (Exception $e) {
            return $this->sendExceptionToHandler($e);
        }
    }

    protected function sendThroughPipeline(array $middleware, Closure $then)
    {
        if (count($middleware) > 0 && ! $this->shouldSkipMiddleware()) {
            return (new Pipeline($this))
                ->send($this->make('request'))
                ->through($middleware)
                ->then($then);
        }

        return $then();
    }

    protected function handleDispatcherResponse($routeInfo)
    {
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new NotFoundHttpException;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedHttpException($routeInfo[1]);
            case Dispatcher::FOUND:
                return $this->handleFoundRoute($routeInfo);
                break;
            default:
                dd('handleDispatcherResponse default case');
        }
    }

    protected function handleFoundRoute($routeInfo)
    {
        $action = $routeInfo[1];

        if (isset($action['middleware'])) {
            $middleware = $this->gatherMiddlewareClassNames($action['middleware']);

            return $this->sendThroughPipeline($middleware, function () use ($routeInfo) {
                return $this->call($routeInfo[1][0], $routeInfo[2]);
            });
        }

        return $this->call($action[0] ,$routeInfo[2]);
    }

    protected function createDispatcher()
    {
        return simpleDispatcher(function ($r) {
            foreach($this->router->getRoutes() as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']);
            }
        });
    }

    protected function gatherMiddlewareClassNames($middleware)
    {
        return array_map(function ($name) {
           list($name, $parameters) = array_pad(explode(':', $name, 2), 2, null);
           return array_get($this->routeMiddleware, $name, $name).($parameters ? ':'.$parameters : '');
        }, $middleware);
    }

    protected function shouldSkipMiddleware()
    {
        return $this->bound('middleware.disable') && $this->make('middleware.disable') === true;
    }

    protected function callTerminableMiddleware($response)
    {
        if ($this->shouldSkipMiddleware()) {
            return;
        }

        foreach ($this->middleware as $middleware) {
            $instance = $this->make(explode(':', $middleware)[0]);
            if (method_exists($instance, 'terminate')) {
                $instance->terminate($this->make('request'), $response);
            }
        }
    }
}