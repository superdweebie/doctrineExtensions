<?php

namespace Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\AccessControl\Mapping\Annotation\DoNotAccessControlUpdate as SDS_DoNotAccessControlUpdate;
use Sds\DoctrineExtensions\Freeze\Mapping\Annotation\FreezeField as SDS_FreezeField;
use Sds\DoctrineExtensions\Freeze\Behaviour\FreezeableTrait;
use Sds\Common\Freeze\FreezeableInterface;

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
