<?php

namespace IntecPhp\Test\Unit\Handler;

use PHPUnit\Framework\TestCase;
use IntecPhp\Handler\PhpError;
use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Log\LoggerInterface;
use Exception;
use IntecPhp\Test\Unit\ResponseTestCase;

class PhpErrorTest extends TestCase
{
    use ResponseTestCase;

    public function testInvokeWithDisplayErrors()
    {
        $response = new Response();
        $ex = new Exception('Some exception');
        $displayErrors = true;
        $expectedJsonResponse = $this->toJson($response, 500, 'Erro inesperado', [
            'type' => get_class($ex),
            'code' => $ex->getCode(),
            'message' => $ex->getMessage(),
            'file' => $ex->getFile(),
            'line' => $ex->getLine(),
            'trace' => explode("\n", $ex->getTraceAsString()),
        ]);
        $this->tryToInvoke($displayErrors, $response, $expectedJsonResponse, $ex);
    }

    public function testInvokeWithoutDisplayErrors()
    {
        $response = new Response();
        $ex = new Exception('Some exception');
        $displayErrors = false;
        $expectedJsonResponse = $this->toJson($response, 500, 'Erro inesperado');
        $this->tryToInvoke($displayErrors, $response, $expectedJsonResponse, $ex);
    }

    private function tryToInvoke(bool $displayErrors, Response $response, Response $expectedJsonResponse, Exception $ex)
    {

        $loggerStub = $this->createMock(LoggerInterface::class);
        $loggerStub
            ->expects($this->atLeastOnce())
            ->method('debug');

        $handler = new PhpError($displayErrors, $loggerStub);
        $request = $this->createMock(Request::class);
        $jsonResponse = $handler($request, $response, $ex);

        $this->checkResponseAssertions($jsonResponse, $expectedJsonResponse);
    }
}
