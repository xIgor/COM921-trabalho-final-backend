<?php

namespace IntecPhp\Test\Functional;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;
use IntecPhp\Service\DbHandler;

abstract class TestCase extends BaseTestCase
{
    protected $app;
    protected $db;

    public function setUp()
    {
        $this->app = $GLOBALS['app'];
        $this->db = $this->app->getContainer()->get(DbHandler::class);
        $this->logger = $this->app->getContainer()->get('logger');
        $this->db->query("insert into usuario(id, apelido, email, senha, papel) values(?, ?, ?, ?, ?)", [
            1,
            'marciao',
            'suporte@incluirtecnologia.com.br',
            '$2y$10$KWCCBmkpsWeKZ7lyvSbSDenlNBZ02OL7SrggykoudhrQC5GjTOrBG', // senha 123456789
            'comum'
        ]);
    }

    protected function runApp($requestMethod, $requestUri, $requestData = null, $token = null)
    {
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );
        $request = Request::createFromEnvironment($environment);
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }

        if($token) {
            $request = $request->withHeader('Authorization', sprintf('Bearer %s', $token));
        }

        $this->logger->info(json_encode([$requestMethod, $requestUri, $requestData, $token]));
        return $this->app->process($request, new Response());
    }

    public function tearDown()
    {
        $this->db->query('delete from usuario');
        $this->app = null;
        $this->db = null;
    }

    protected function decodeResponse(Response $response, $asArray = true)
    {
        $this->logger->info($response->getBody());
        return json_decode($response->getBody(), $asArray);
    }
}
