<?php

namespace Laravel\Lumen\Concerns;

use Exception;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

trait RoutesRequests
{
    public function handle(SymfonyRequest $request)
    {
        $response = $this->dispatch($request);
        return $response;
    }

    public function dispatch($request = null)
    {
        try {
            return null;
        } catch (Exception $e) {
            return 'meh';
        }
    }
}