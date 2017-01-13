<?php

namespace Avtonom\WebGateBundle\Exception;

class WebGateException extends \Exception
{
    /**
     * @var string
     */
    protected $contentToJson;

    /**
     * @param bool|true $decode
     *
     * @return string
     *
     */
    public function getContentToJson($decode = true)
    {
        return ($decode) ? json_decode($this->contentToJson, true) : $this->contentToJson;
    }

    /**
     * @param string $contentToJson
     */
    public function setContentToJson($contentToJson)
    {
        $this->contentToJson = $contentToJson;
    }

    /**
     * @param bool|true $decode
     * @return string
     */
    public function getContent($decode = true)
    {
        return (!empty($this->getMessage())) ? : $this->getContentToJson($decode);
    }
}
