<?php

namespace IntecPhp\Middleware;

use IntecPhp\Service\JwtWrapper;
use IntecPhp\Helper\JsonResponse;

class Auth
{
    use JsonResponse;

    private $jwt;

    public function __construct(JwtWrapper $jwt)
    {
        $this->jwt = $jwt;
    }

    public function __invoke($request, $response, $next)
    {
        $header = $request->getHeader('Authorization');
        $token = $header ? $this->getToken($header[0]) : '';
        $data = $this->jwt->decode($token);
        if (!$token || !$data) {
            return $this->toJson($response, 401, 'Token de acesso não informado ou inválido');
        }
        $req = $request->withAttribute('auth', (array)$data);
        return $next($req, $response);
    }

    private function getToken($header)
    {
        if(preg_match("/Bearer\s(\S+)/", $header, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
