<?php

namespace Laravel\Lumen\Concerns;

use Closure;
use Exception;
use FastRoute\Dispatcher;
use Laravel\Lumen\Routing\Closure as RoutingClosure;
use Symfony\Component\EventDispatcher\Tests\DependencyInjection\SubscriberService;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait OldRoutesRequests
{
    protected $middleware = [];
    protected $dispatcher;


    public function handle(SymfonyRequest $request)
    {
        $response = $this->dispatch($request);
        return $response;
    }

    public function getRouteInfo(SymfonyRequest $request)
    {
        list($method, $pathInfo) = $this->parseIncomingRequest($request);
        return $this->createDispatcher()->dispatch($method, $pathInfo);
    }

    public function dispatch($request = null)
    {
        list($method, $pathInfo) = $this->parseIncomingRequest($request);

        return $this->sendThroughPipeline($this->middleware, function () use ($method, $pathInfo) {
            if (isset($this->router->getRoutes()[$method.$pathInfo])) {
                return $this->handleFoundRoute([true, $this->router->getRoutes()[$method.$pathInfo]['action'], []]);
            }
        });
}

    protected function sendThroughPipeline(array $middleware, Closure $then)
    {
        return $then();
    }

    protected function handleDispatcherResponse($routeInfo)
    {
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                dd('not found');
                throw new NotFoundHttpException;
            case Dispatcher::METHOD_NOT_ALLOWED:
                dd('not allowed');
                throw new MethodNotAllowedHttpException($routeInfo[1]);
            case Dispatcher::FOUND:
                return $this->handleFoundRoute($routeInfo);
        }
    }

    protected function createDispatcher()
    {
        return $this->dispatcher ?: \FastRoute\simpleDispatcher(function ($r) {
            foreach ($this->router->getRoutes() as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']);
            }
        });
    }


    protected function parseIncomingRequest($request)
    {
        return [$request->getMethod(), '/'.trim($request->getPathInfo(), '/')];
    }

    public function prepareResponse($response)
    {
        return $response;
    }

    protected function handleFoundRoute($routeInfo)
    {
        return $this->prepareResponse(
          $this->callActionOnArrayBasedRoute($routeInfo)
        );
    }

    protected function callActionOnArrayBasedRoute($routeInfo)
    {
        $action = $routeInfo[1];
        foreach ($action as $value) {
            if ($value instanceof Closure) {
                $closure = $value->bindTo(new RoutingClosure);
                break;
            }
        }
        return $this->prepareResponse($this->call($closure, $routeInfo[2]));
    }
}