<?php

namespace IntecPhp\Test\Unit;

use Slim\Http\Request;
use Slim\Http\Response;
use IntecPhp\Helper\JsonResponse;

trait ResponseTestCase
{
    use JsonResponse;

    protected function checkResponseAssertions(Response $jsonResponse, Response $expectedJsonResponse)
    {
        $jsonResponse->getBody()->rewind();
        $expectedJsonResponse->getBody()->rewind();

        $this->assertNotSame($expectedJsonResponse, $jsonResponse);
        $this->assertEquals($expectedJsonResponse->getBody()->getContents(), $jsonResponse->getBody()->getContents());
        $this->assertEquals($expectedJsonResponse->getStatusCode(), $jsonResponse->getStatusCode());
    }
}
