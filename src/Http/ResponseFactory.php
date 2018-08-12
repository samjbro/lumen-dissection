<?php

namespace Laravel\Lumen\Http;

use Illuminate\Http\Response;

class ResponseFactory
{
    public function make($content = '', $status = 200, array $headers = [])
    {
        // Returns a new instance of Illuminate's Response class with the supplied parameters
        return new Response($content, $status, $headers);
    }

}