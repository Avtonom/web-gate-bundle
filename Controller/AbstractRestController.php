<?php

namespace Avtonom\WebGateBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Avtonom\WebGateBundle\Exception\WebGateException;

abstract class AbstractRestController extends FOSRestController
{
    /**
     * @param string $serviceName
     * @param array $data
     *
     * @return JsonResponse
     *
     * @throws NotFoundHttpException
     * @throws WebGateException
     */
    protected function send($serviceName, $data)
    {
        $responseData = $this->_send($serviceName, $data);
        if (!$responseData) {
            throw new NotFoundHttpException(sprintf('The resource \'%d\' was not found.', print_r($data, true)));
        }
        return new JsonResponse($responseData);
    }

    /**
     * @param string $serviceName
     * @param array $data
     * @param bool|false $returnXml
     *
     * @return \stdClass|array
     *
     * @throws WebGateException
     */
    protected function _send($serviceName, $data, $returnXml = false)
    {
        $this->get('web_gate.logger')->addInfo(__METHOD__.PHP_EOL.'INPUT: '.print_r($data, true));
        return $this->get($serviceName)->send($data, $returnXml);
    }
}