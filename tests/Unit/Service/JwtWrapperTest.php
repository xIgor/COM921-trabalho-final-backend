<?php

namespace IntecPhp\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use IntecPhp\Service\JwtWrapper;

class JwtWrapperTest extends TestCase
{
    public function testEncode()
    {
        $jwt = new JwtWrapper('magicsecret', 10);
        $data = [
            'some' => 'data'
        ];
        $this->assertTrue(is_string($jwt->encode($data)));
    }

    public function testDecodeSuccess()
    {
        $jwt = new JwtWrapper('magicsecret', 10);
        $data = [
            'id' => 10,
            'some' => 'data'
        ];
        $token = $jwt->encode($data);
        $this->assertEquals($data, (array)$jwt->decode($token));
    }

    public function testExpiredToken()
    {
        $jwt = new JwtWrapper('magicsecret', 0, -1);
        $data = [
            'id' => 10,
            'some' => 'data'
        ];

        $token = $jwt->encode($data);
        $this->assertFalse($jwt->decode($token));
    }

    public function testBeforeValidToken()
    {
        $jwt = new JwtWrapper('magicsecret', 20, 10);
        $data = [
            'id' => 10,
            'some' => 'data'
        ];

        $token = $jwt->encode($data);
        $this->assertFalse($jwt->decode($token));
    }

    public function testInvalidToken()
    {
        $jwt = new JwtWrapper('magicsecret', 5000);
        $data = [
            'id' => 10,
            'some' => 'data'
        ];

        $jwt->encode($data);
        $this->assertFalse($jwt->decode('???'));
    }
}
