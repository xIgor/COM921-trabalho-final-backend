<?php

namespace IntecPhp\Action;

use IntecPhp\Helper\JsonResponse;
use IntecPhp\Model\Consumer;

class ListCompaniesWithMoreComplaints
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
            if (empty($params['state'])) {
                throw new \Exception('Não foi submetido o estado');
            }

            $data = $this->consumer->getCompaniesWithMoreComplaints($params['state']);

            return $this->toJson($response, 200, 'Ok', ['companies' => $data]);
        } catch (\Exception $e) {
            return $this->toJson($response, 400, $e->getMessage());
        }
    }
}
