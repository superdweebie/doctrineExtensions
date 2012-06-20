<?php

namespace SdsDoctrineExtensionsTest\Serializer\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Serializer\Mapping\Annotation\SerializeGetter as SDS_SerializeGetter;

/** @ODM\Document */
class GetMethodError {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;
    
    /**
     * @ODM\Field(type="string")
     * @SDS_SerializeGetter("broken")
     */
    protected $name;

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}

