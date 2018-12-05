<?php

namespace IntecPhp\Action;

use IntecPhp\Helper\JsonResponse;
use IntecPhp\Model\Consumer;

class ListComplaintRate
{
    use JsonResponse;

    private $consumer;

    public function __construct(Consumer $consumer)
    {
        $this->consumer = $consumer;
    }

    public function __invoke($request, $response)
    {
        try {
            $data = $this->consumer->calculateRegionsComplaintRate();

            return $this->toJson($response, 200, 'Ok', ['regions' => $data]);
        } catch (\Exception $e) {
            return $this->toJson($response, 400, $e->getMessage());
        }
    }
}
