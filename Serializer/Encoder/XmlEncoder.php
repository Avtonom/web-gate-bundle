<?php

namespace Avtonom\WebGateBundle\Serializer\Encoder;

//use Symfony\Component\Serializer\Encoder\XmlEncoder as BaseXmlEncoder;

class XmlEncoder// extends BaseXmlEncoder
{
    public function normalize($data)
    {
        if(!is_array($data) || empty($data)){
            return $data;
        }
        $result = [];
        foreach($data as $key => $value){
            if($key === "#"){
                continue;
            }
            if(substr($key, 0, 1) === '@'){
                $key = substr($key, 1);
            }
            if(is_array($value)){
                $value = $this->normalize($value);
            }
            $result[$key] = $value;
        }
        return $result;
    }
}