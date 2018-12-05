<?php

namespace IntecPhp\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Slim\Http\Response;
use IntecPhp\Helper\JsonResponse;

class JsonResponseTest extends TestCase
{
    public function testReturnsCorrectDefaultResponse()
    {
        $resp = new Response();
        $arr = $this->getResponseDataArray();
        $jsonResponse = $this->getResponseFromTrait([$resp]);
        $res = json_decode($jsonResponse->getBody()->getContents(), true);

        $this->assertEquals($jsonResponse->getStatusCode(), $arr['code']);
        $this->assertEquals($jsonResponse->getHeader('content-type'), ['application/json;charset=utf-8']);
        $this->assertEquals($res, $arr);
    }

    public function testReturnsCorrectResponse()
    {
        $code = 201;
        $message = 'created';
        $data = ['age' => 26, 'birth' => '1991-12-06'];
        $resp = new Response();

        $arr = $this->getResponseDataArray($code, $message, $data);
        $jsonResponse = $this->getResponseFromTrait([$resp, $code, $message, $data]);
        $res = json_decode($jsonResponse->getBody()->getContents(), true);

        $this->assertEquals($jsonResponse->getStatusCode(), $code);
        $this->assertEquals($jsonResponse->getHeader('content-type'), ['application/json;charset=utf-8']);
        $this->assertEquals($res, $arr);
    }

    private function getResponseDataArray(int $code = 200, string $message = 'ok', array $data = [])
    {
        return [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
    }

    private function getResponseFromTrait(array $args)
    {
        $mock = $this->getMockForTrait(JsonResponse::class);
        $reflection = new \ReflectionClass(get_class($mock));
        $method = $reflection->getMethod('toJson');
        $method->setAccessible(true);
        $jsonResponse = $method->invokeArgs($mock, $args);
        $jsonResponse->getBody()->rewind();

        return $jsonResponse;
    }
}
