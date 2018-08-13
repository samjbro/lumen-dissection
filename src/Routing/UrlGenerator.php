<?php

namespace Laravel\Lumen\Routing;

use Laravel\Lumen\Application;

class UrlGenerator
{
    protected $app;
    protected $cachedSchema;
    protected $cachedRoot;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function to($path, $extra = [], $secure = null)
    {
        $scheme = $this->formatScheme($secure);

        $root = $this->getRootUrl($scheme);
        return $this->trimUrl($root, $path);
    }

    public function route($name, $parameters = [], $secure = null)
    {
        $uri = $this->app->router->namedRoutes[$name];

        $uri = preg_replace_callback('/\{(.*?)(:.*?)?(\{[0-9,]+\})?\}/', function ($m) use (&$parameters) {
            return isset($parameters[$m[1]]) ? array_pull($parameters, $m[1]) : $m[0];
        }, $uri);

        $uri = $this->to($uri, [], $secure);

        if (! empty($parameters)) {
            $uri .= '?'.http_build_query($parameters);
        }

        return $uri;
    }

    public function formatScheme($secure)
    {
        if (is_null($this->cachedSchema)) {
            $this->cachedSchema = $this->app->make('request')->getScheme().'://';
        }
        return $this->cachedSchema;
    }

    protected function getRootUrl($scheme, $root = null)
    {
        if (is_null($root)) {
            if (is_null($this->cachedRoot)) {
                $this->cachedRoot = $this->app->make('request')->root();
            }

            $root = $this->cachedRoot;
        }

        return $root;
    }

    protected function trimUrl($root, $path, $tail = '')
    {
        return trim($root.'/'.trim($path, '/'), '/');
    }
}