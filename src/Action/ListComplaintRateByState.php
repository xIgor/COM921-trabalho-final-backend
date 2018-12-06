<?php

namespace IntecPhp\Action;

use IntecPhp\Helper\JsonResponse;
use IntecPhp\Model\Consumer;

class ListComplaintRateByState
{
    use JsonResponse;

    private $consumer;

    public function __construct(Consumer $consumer)
    {
        $this->consumer = $consumer;
    }

    public function __invoke($request, $response)
    {
        $params = $request->getParams();

        try {
            if (empty($params['region'])) {
                throw new \Exception('Não foi submetida a região');
            }

            $data = $this->consumer->calculateStateComplaintRate($params['region']);

            return $this->toJson($response, 200, 'Ok', ['states' => $data]);
        } catch (\Exception $e) {
            return $this->toJson($response, 400, $e->getMessage());
        }
    }
}
