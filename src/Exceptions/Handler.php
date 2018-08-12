<?php

namespace Laravel\Lumen\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;

class Handler implements ExceptionHandler
{

    /**
     * Report or log an exception.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        // TODO: Implement report() method.
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        // TODO: Implement render() method.
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @param  \Exception $e
     * @return void
     */
    public function renderForConsole($output, Exception $e)
    {
        // TODO: Implement renderForConsole() method.
    }
}