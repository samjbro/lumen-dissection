<?php

use PHPUnit\Framework\TestCase;
use Laravel\Lumen\Application;
use Illuminate\Http\Request;

class FullApplicationTest extends TestCase
{
    public function testBasicRequest()
    {
        $app = new Application;

        $app->router->get('/', function () {
            return response('Hello World');
        });

        $response = $app->handle(Request::create('/', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }
}