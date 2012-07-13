<?php

namespace Sds\DoctrineExtensions\Test\Stamp\TestAsset\Document;

use Sds\DoctrineExtensions\Stamp\Behaviour\StampTrait;
use Sds\Common\Stamp;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/** @ODM\Document */
class Simple implements
    Stamp\CreatedByInterface,
    Stamp\CreatedOnInterface,
    Stamp\UpdatedByInterface,
    Stamp\UpdatedOnInterface
{
    use StampTrait;

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
