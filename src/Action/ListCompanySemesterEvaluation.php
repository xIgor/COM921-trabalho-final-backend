<?php

namespace IntecPhp\Action;

use IntecPhp\Helper\JsonResponse;
use IntecPhp\Model\Consumer;

class ListCompanySemesterEvaluation
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
            if (empty($params['company'])) {
                throw new \Exception('Não foi submetida a empresa');
            }

            $data = $this->consumer->getCompanySemesterEvaluation($params['company']);

            return $this->toJson($response, 200, 'Ok', ['evaluation' => $data]);
        } catch (\Exception $e) {
            return $this->toJson($response, 400, $e->getMessage());
        }
    }
}
