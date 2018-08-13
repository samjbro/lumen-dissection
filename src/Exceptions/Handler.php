<?php

namespace Laravel\Lumen\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Illuminate\Http\Response;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

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
        dd('Handler report');
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
        $fe = FlattenException::create($e);

        $response = new Response('', $fe->getStatusCode(), $fe->getHeaders());
        $response->exception = $e;

        return $response;
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