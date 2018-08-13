<?php

use Illuminate\Container\Container;

if (! function_exists('app')) {
    function app($make = null)
    {
        if (is_null($make)) {
            return Container::getInstance();
        }
        return Container::getInstance()->make($make);
    }
}

if (! function_exists('response')) {
    function response($content = '', $status = 200, array $headers = [])
    {
        // Instantiates Lumen's ResponseFactory, which is a wrapper around the Illuminate Response class
        $factory = new Laravel\Lumen\Http\ResponseFactory;

        // Tells the ResponseFactory wrapper to return a new Response instance with the given parameters
        return $factory->make($content, $status, $headers);
    }
}

if (! function_exists('url')) {
    function url($path = null, $parameters = [], $secure = null)
    {
        return app('url')->to($path, $parameters, $secure);
    }
}

if (! function_exists('route')) {
    function route($name, $parameters = [], $secure = null)
    {
        return app('url')->route($name, $parameters, $secure);
    }
}