<?php

if (! function_exists('response')) {
    function response($content = '', $status = 200, array $headers = [])
    {
        // Instantiates Lumen's ResponseFactory, which is a wrapper around the Illuminate Response class
        $factory = new Laravel\Lumen\Http\ResponseFactory;

        // Tells the ResponseFactory wrapper to return a new Response instance with the given parameters
        return $factory->make($content, $status, $headers);
    }
}