<?php

namespace IntecPhp\Test\Unit\Handler;

use PHPUnit\Framework\TestCase;
use IntecPhp\Handler\NotFound;
use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Log\LoggerInterface;
use IntecPhp\Test\Unit\ResponseTestCase;
use Slim\Http\Uri;

class NotFoundTest extends TestCase
{
    use ResponseTestCase;

    public function testInvoke()
    {
        $response = new Response();
        $expectedJsonResponse = $this->toJson($response, 404, 'Recurso não encontrado');
        $logger = $this->createMock(LoggerInterface::class);
        $logger->method('debug');
        $handler = new NotFound($logger);

        $uri = $this->createMock(Uri::class);
        $uri
            ->method('getPath')
            ->willReturn('/hello');

        $request = $this->createMock(Request::class);
        $request
            ->method('getUri')
            ->willReturn($uri);

        $jsonResponse = $handler($request, $response);

        $this->checkResponseAssertions($jsonResponse, $expectedJsonResponse);
    }
}
