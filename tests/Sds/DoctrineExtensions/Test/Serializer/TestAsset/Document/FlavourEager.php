<?php

namespace Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\Serializer(@Sds\ClassName)
 */
class FlavourEager
{

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /** @ODM\String */
    protected $name;

    /**
     * @ODM\ReferenceMany(targetDocument="CakeEager")
     * @Sds\Serializer(@Sds\Eager)
     */
    protected $cakes;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getCakes() {
        return $this->cakes;
    }

    public function setCakes(array $cakes) {
        $this->cakes = $cakes;
    }

    public function addCake(CakeEager $cake) {
        $this->cakes[] = $cake;
    }

    public function __construct($name) {
        $this->name = $name;
    }
}
