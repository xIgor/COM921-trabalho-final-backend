<?php

namespace IntecPhp\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use IntecPhp\Helper\JsonResponse;

class NotFound
{
    use JsonResponse;

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->logger->debug(sprintf(
            '404: route: %s. params: %s',
            $request->getUri()->getPath(),
            json_encode($request->getParams())
        ));
        return $this->toJson($response, 404, 'Recurso não encontrado');
    }
}
