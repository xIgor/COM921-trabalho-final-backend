<?php

namespace IntecPhp\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use IntecPhp\Helper\JsonResponse;

class PhpError
{
    use JsonResponse;

    private $displayErrorDetails;
    private $logger;

    public function __construct(bool $errorDetails, LoggerInterface $logger)
    {
        $this->displayErrorDetails = $errorDetails;
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Throwable $ex)
    {
        $this->logger->debug($ex->getMessage());
        $errorData = [];
        if($this->displayErrorDetails) {
            $errorData = [
                'type' => get_class($ex),
                'code' => $ex->getCode(),
                'message' => $ex->getMessage(),
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'trace' => explode("\n", $ex->getTraceAsString()),
            ];
        }

        return $this->toJson($response, 500, 'Erro inesperado', $errorData);
    }
}
