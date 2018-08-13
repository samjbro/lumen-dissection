<?php

namespace Laravel\Lumen\Concerns;

use Error;

trait RegistersExceptionHandlers
{
    protected function sendExceptionToHandler($e)
    {
        $handler = $this->resolveExceptionHandler();

        return $handler->render($this->make('request'), $e);
    }

    protected function resolveExceptionHandler()
    {
        return $this->make('Laravel\Lumen\Exceptions\Handler');
    }
}