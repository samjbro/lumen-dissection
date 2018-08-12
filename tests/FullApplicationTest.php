<?php

use PHPUnit\Framework\TestCase;
use Laravel\Lumen\Application;
use Illuminate\Http\Request;

class FullApplicationTest extends TestCase
{
    public function testBasicRequest()
    {
        // Instantiates the application
        $app = new Application;

        // Defines a GET route at uri '/' that will execute the given callback
        $app->router->get('/', function () {

            // calls the 'response' helper function
            return response('Hello World');
        });

        // Creates a new Illuminate GET request to the '/' uri and tells the Lumen application to handle it
        $response = $app->handle(Request::create('/', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }
}