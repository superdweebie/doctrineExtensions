<?php

namespace Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\AccessControl\Mapping\Annotation\DoNotAccessControlUpdate as SDS_DoNotAccessControlUpdate;
use Sds\DoctrineExtensions\Freeze\Mapping\Annotation\FreezeField as SDS_FreezeField;
use Sds\DoctrineExtensions\Freeze\Behaviour\FreezeStampTrait;
use Sds\Common\Freeze\FrozenByInterface;
use Sds\Common\Freeze\FrozenOnInterface;
use Sds\Common\Freeze\ThawedByInterface;
use Sds\Common\Freeze\ThawedOnInterface;
use Sds\DoctrineExtensions\Freeze\Behaviour\FreezeableTrait;
use Sds\Common\Freeze\FreezeableInterface;

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
