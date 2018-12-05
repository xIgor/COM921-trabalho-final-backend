<?php

namespace IntecPhp\Test\Unit\Middleware;

use PHPUnit\Framework\TestCase;
use IntecPhp\Middleware\Auth;
use IntecPhp\Service\JwtWrapper;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;
use IntecPhp\Test\Unit\ResponseTestCase;

class AuthTest extends TestCase
{
    use ResponseTestCase;

    public function testBearerTokens()
    {
        $mock = $this
            ->getMockBuilder(Auth::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflection = new \ReflectionClass(get_class($mock));
        $method = $reflection->getMethod('getToken');
        $method->setAccessible(true);

        $this->assertNull($method->invokeArgs($mock, ['Beaar aaaaa']));
        $this->assertSame('aaaaa', $method->invokeArgs($mock, ['Bearer aaaaa']));
        $this->assertNull($method->invokeArgs($mock, ['Beaar aaa aa']));
    }

    public function testBlockInvalidToken()
    {
        $token = 'aninvalidtoken';
        $response = new Response();
        $expectedJsonResponse = $this->toJson($response, 401, 'Token de acesso não informado ou inválido');
        $env = Environment::mock();
        $req = Request::createFromEnvironment($env)->withHeader('Authorization', 'Bearer ' . $token);
        $stubJwt = $this->createMock(JwtWrapper::class);
        $stubJwt
            ->method('encode')
            ->with($token)
            ->willReturn(false);

        $auth = new Auth($stubJwt);
        $jsonResponse = $auth($req, $response, function($req, $resp) {
            return null;
        });

        $this->checkResponseAssertions($jsonResponse, $expectedJsonResponse);
    }

    public function testAllowValidToken()
    {
        $token = '==valid==token==';
        $tokenData = [
            'id' => 1
        ];
        $response = new Response();
        $env = Environment::mock();
        $req = Request::createFromEnvironment($env)->withHeader('Authorization', 'Bearer ' . $token);
        $stubJwt = $this->createMock(JwtWrapper::class);
        $stubJwt
            ->method('decode')
            ->with($token)
            ->willReturn($tokenData);

        $auth = new Auth($stubJwt);
        $testCase = $this;
        $returnResp = $auth($req, $response, function($req, $resp) use ($testCase, $tokenData) {
            $testCase->assertSame($tokenData, $req->getAttribute('auth'));
            return $resp;
        });

        $this->assertSame($response, $returnResp);
    }
}
