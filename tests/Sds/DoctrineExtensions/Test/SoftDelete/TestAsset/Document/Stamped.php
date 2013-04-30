<?php

namespace Sds\DoctrineExtensions\Test\SoftDelete\TestAsset\Document;

use Sds\DoctrineExtensions\SoftDelete\DataModel\SoftDeleteStampTrait;
use Sds\DoctrineExtensions\SoftDelete\DataModel\SoftDeleteableTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/** @ODM\Document */
class Stamped {

    use SoftDeleteableTrait;
    use SoftDeleteStampTrait;

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\String
     */
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
}
