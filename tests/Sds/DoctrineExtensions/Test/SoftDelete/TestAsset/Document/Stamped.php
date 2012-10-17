<?php

namespace Sds\DoctrineExtensions\Test\SoftDelete\TestAsset\Document;

use Sds\DoctrineExtensions\SoftDelete\DataModel\SoftStampTrait;
use Sds\Common\SoftDelete\SoftDeletedByInterface;
use Sds\Common\SoftDelete\SoftDeletedOnInterface;
use Sds\Common\SoftDelete\RestoredByInterface;
use Sds\Common\SoftDelete\RestoredOnInterface;
use Sds\DoctrineExtensions\SoftDelete\DataModel\SoftDeleteableTrait;
use Sds\Common\SoftDelete\SoftDeleteableInterface;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/** @ODM\Document */
class Stamped implements
    SoftDeleteableInterface,
    SoftDeletedByInterface,
    SoftDeletedOnInterface,
    RestoredByInterface,
    RestoredOnInterface
{
    use SoftDeleteableTrait;
    use SoftStampTrait;

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
