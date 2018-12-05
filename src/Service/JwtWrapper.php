<?php

namespace IntecPhp\Service;

use Firebase\JWT\JWT;
use Exception;

class JwtWrapper
{
    private $secret;
    private $duration;
    private $issuedAt;

    public function __construct(string $secret, int $duration, int $issuedAtOffset = 0)
    {
        $this->secret = $secret;
        $this->duration = $duration;
        $this->issuedAt = time() + $issuedAtOffset;
    }

    public function encode(array $info)
    {
        $tokenParams = [
            'iat'  => $this->issuedAt,
            'exp'  => $this->issuedAt + $this->duration,
            'nbf'  => $this->issuedAt - 1,
            'data' => $info,
        ];

        return JWT::encode($tokenParams, $this->secret);
    }

    public function decode(string $jwtToken)
    {
        try {
            return JWT::decode($jwtToken, $this->secret, ['HS256'])->data;
        } catch(Exception $e) {
            return false;
        }
    }
}
