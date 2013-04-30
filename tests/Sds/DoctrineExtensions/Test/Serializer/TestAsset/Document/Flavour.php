<?php

namespace Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\Serializer\ClassName
 */
class Flavour
{

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /** @ODM\String */
    protected $name;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function __construct($name){
        $this->name = $name;
    }
}
