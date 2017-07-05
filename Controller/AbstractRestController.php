<?php

namespace Avtonom\WebGateBundle\Controller;

use Avtonom\WebGateBundle\Service\SoapService;
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
     * @param string|null $resource
     *
     * @return JsonResponse
     *
     * @throws NotFoundHttpException
     * @throws WebGateException
     */
    protected function send($serviceName, $data, $resource = null)
    {
        $responseData = $this->_send($serviceName, $data, $resource);
        if (!$responseData) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.', print_r($data, true)));
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
        /** @var SoapService $service */
        $service = $this->get($serviceName);
        $this->get('web_gate.logger')->addInfo(__METHOD__, [$service->getMethodName(), $data]);
        return $service->send($data, $returnXml);
    }
}
