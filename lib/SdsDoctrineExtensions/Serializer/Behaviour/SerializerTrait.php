<?php

namespace SdsDoctrineExtensions\Serializer\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Serializer\SerializerService;

trait SerializerTrait {
 
    public function jsonSerialize(){
        $serializerService = SerializerService::getInstance();
        return $serializerService->serializeArray($this);
    }
}