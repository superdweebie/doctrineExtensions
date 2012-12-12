<?php

namespace Sds\DoctrineExtensions\Test\Rest\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\RestUrl("RestAPI/Explicit")
 */
class Explicit {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    public function getId() {
        return $this->id;
    }
}
