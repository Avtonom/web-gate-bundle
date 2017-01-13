<?php

namespace Avtonom\WebGateBundle\Service;

use Buzz\Browser;
use Buzz\Listener\ListenerChain;
use Buzz\Listener\ListenerInterface;
use Symfony\Bridge\Monolog\Logger;

use Buzz\Listener\BasicAuthListener;
use Buzz\Message\Form\FormRequest;
use Avtonom\WebGateBundle\Exception\WebGateException;

class RestService
{
    protected $method;
    protected $resource;
    protected $host;

    protected $login;
    protected $password;

    protected $useCookie;

    /**
     * @var Logger $logger
     */
    private $logger;

    /**
     * @var Browser
     */
    private $client;

    /**
     * @param Logger $logger
     * @param $client
     * @param $method
     * @param $host
     * @param $resource
     * @param string|null $login
     * @param string|null $password
     * @param bool|false $useCookie
     */
    function __construct(Logger $logger, $client, $method, $host, $resource, $login = null, $password = null, $useCookie = false)
    {
        $this->logger = $logger;
        $this->client = $client;

        $this->method = $method;
        $this->host = $host;
        $this->resource = $resource;

        $this->login = $login;
        $this->password = $password;

        $this->useCookie = $useCookie;

        $this->client->getClient()->setOption(CURLINFO_HEADER_OUT, true);

        if($this->login){
            $listener = new BasicAuthListener($this->login, $this->password);
            if(!$this->hasListener($this->client, $listener)){
                $this->logger->addDebug('BasicAuthListener: '.$this->login.' '.substr($this->password, 3, 7));
                $this->client->addListener($listener);
            }
        }
        if($this->useCookie) {
            $listener = new \Avtonom\WebGateBundle\Listener\CookieListener();
            if(!$this->hasListener($this->client, $listener)){
                $this->logger->addDebug('CookieListener');
                $this->client->addListener($listener);
            }
        }
    }

    /**
     * @param array $data
     *
     * @return array|string|null
     *
     * @throws \Exception
     */
    public function send($data = null)
    {
        $request = new FormRequest($this->method, $this->resource, $this->host);
        if($data){
            $request->addFields($data);
        }
        try {
            $this->logger->addDebug(vsprintf('Request: %s %s', [$this->method, $request->getUrl()]));
            /** @var Buzz\Message\Response $response */
            $response = $this->client->send($request);
            $this->logger->addDebug('Response: '.$response->getStatusCode().' '.substr($response->getContent(), 0, 300).PHP_EOL.var_export($this->client->getClient()->getInfo(), true));
        } catch(\Exception $e) {
            switch($e->getCode()){
                case 28:
                    $code = 504;
                    break;
                default:
                    $code = ($e->getCode() >= 100) ? $e->getCode() : 502;
            }
            $this->logger->addCritical(PHP_EOL.__METHOD__.sprintf('[%s/%s] %s', $e->getCode(), $code, $e->getMessage()));
            throw new WebGateException($e->getMessage(), $code, $e);
        }
        if($response->getStatusCode() < 200 || $response->getStatusCode() >= 300){
            switch($response->getStatusCode()){
                case 500:
                    $code = 502;
                    break;
                default:
                    $code = $response->getStatusCode();
            }
            $webGateException = new WebGateException('', $code);
            $webGateException->setContentToJson($response->getContent());
            throw $webGateException;
        }
        return json_decode($response->getContent(), true);
    }

    /**
     * @param Browser $client
     * @param ListenerInterface $listener
     *
     * @return bool
     */
    protected function hasListener(Browser $client, ListenerInterface $listener)
    {
        /** @var ListenerInterface $listenerClient */
        if(!$listenerClient = $client->getListener()){
            return false;
        }
        if(!$listenerClient instanceof ListenerChain){
            return $listenerClient instanceof $listener;
        }
        /** @var ListenerChain $listenerBrowser */
        /** @var ListenerInterface $listenerItem */
        foreach ($listenerClient->getListeners() as $listenerItem) {
            if($listenerItem instanceof $listener){
                return true;
            }
        }
        return false;
    }
}
