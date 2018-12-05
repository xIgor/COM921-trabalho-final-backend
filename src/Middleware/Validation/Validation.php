<?php

namespace IntecPhp\Middleware\Validation;

use Zend\InputFilter\InputFilterProviderInterface;
use Zend\InputFilter\Factory;
use IntecPhp\Helper\JsonResponse;


/**
 * Validação de dados enviados para a api
 * https://docs.zendframework.com/zend-validator/
 */
abstract class Validation implements InputFilterProviderInterface
{
    use JsonResponse;

    final public function __invoke($request, $response, $next)
    {
        $filter = $this->createInputFilter();
        $filter->setData($request->getParsedBody());
        if($filter->isValid()) {
            $params = $filter->getValues();
            $req = $request->withAttribute('params', $params);
            return $next($req, $response);
        }
        $messages = $this->parseErrorMessages($filter->getMessages());
        return $this->toJson($response, 400, 'Campos inválidos', $messages);
    }

    private function createInputFilter()
    {
        $factory = new Factory();
        $spec = $this->getInputFilterSpecification();
        return $factory->createInputFilter($spec);
    }

    /**
     * Altera o formato dos erros
     *
     * Converte o formato de:
     * [
     *      'field1' => [
     *          'errorType1' => 'error message 1'
     *          'errorType2' => 'error message 2'
     *      ],
     *      'field2' => [
     *          'errorType1' => 'error message 1'
     *          'errorType2' => 'error message 2'
     *      ]
     * ]
     *
     * para:
     *
     * [
     *      'field1' => [
     *          'error message 1',
     *          'error message 2'
     *      ],
     *      'field2' => [
     *          'error message 1',
     *          'error message 2'
     *      ]
     * ]
     *
     * @return array Erros reformatados
     */
    private function parseErrorMessages(array $inputFilterMessages)
    {
        $errors = array_map(function($el) {
            return array_values($el);
        }, $inputFilterMessages);

        return array_combine(array_keys($inputFilterMessages), $errors);
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    abstract public function getInputFilterSpecification();
}
