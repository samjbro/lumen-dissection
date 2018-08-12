<?php

use Laravel\Lumen\Http\ResponseFactory;
use \Symfony\Component\HttpFoundation\Response;
class ResponseFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testMakeDefaultResponse()
    {
        $content = 'hello';
        $responseFactory = new ResponseFactory();
        $response = $responseFactory->make($content);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($content, $response->getContent());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}