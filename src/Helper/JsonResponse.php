<?php

namespace IntecPhp\Helper;

use Psr\Http\Message\ResponseInterface;

trait JsonResponse
{
    protected function toJson(
        ResponseInterface $response,
        $statusCode = 200,
        $message = 'ok',
        array $data = []
    ) : ResponseInterface
    {
        return $response->withJson([
            'code' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
