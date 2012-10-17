<?php

namespace Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document;

use Sds\Common\Freeze\FreezeableInterface;
use Sds\Common\Freeze\FrozenByInterface;
use Sds\Common\Freeze\FrozenOnInterface;
use Sds\Common\Freeze\ThawedByInterface;
use Sds\Common\Freeze\ThawedOnInterface;
use Sds\DoctrineExtensions\Freeze\DataModel\FreezeableTrait;
use Sds\DoctrineExtensions\Freeze\DataModel\FreezeStampTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/** @ODM\Document */
class Stamped implements
    FreezeableInterface,
    FrozenByInterface,
    FrozenOnInterface,
    ThawedByInterface,
    ThawedOnInterface
{
    use FreezeableTrait;
    use FreezeStampTrait;

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
