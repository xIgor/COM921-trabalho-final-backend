<?php

namespace IntecPhp\Test\Unit\Middleware;

use PHPUnit\Framework\TestCase;
use IntecPhp\Middleware\AllowOrigin;
use Slim\Http\Request;
use Slim\Http\Response;
use IntecPhp\Test\Unit\ResponseTestCase;

class AllowOriginTest extends TestCase
{
    use ResponseTestCase;

    public function testCorsHeaders()
    {
        $fakeReq = $this->createMock(Request::class);
        $aOrigin = new AllowOrigin();
        $response = $aOrigin($fakeReq, new Response, function($req, $resp) {
            return $resp;
        });
        $this->assertSame(
            $response->getHeaderLine('Access-Control-Allow-Origin'),
            '*'
        );
        $this->assertSame(
            $response->getHeaderLine('Access-Control-Allow-Headers'),
            'X-Requested-With, Content-Type, Accept, Origin, Authorization'
        );
        $this->assertSame(
            $response->getHeaderLine('Access-Control-Allow-Methods'),
            'GET, POST, PUT, DELETE, PATCH, OPTIONS'
        );
    }
}
