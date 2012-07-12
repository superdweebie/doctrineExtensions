<?php

namespace Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document;

use Sds\DoctrineExtensions\Freeze\Behaviour\FreezeableTrait;
use Sds\Common\Freeze\FreezeableInterface;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/** @ODM\Document */
class Simple implements FreezeableInterface {

    use FreezeableTrait;

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
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
