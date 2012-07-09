<?php

namespace Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\Common\AccessControl\AccessControlledInterface;
use Sds\Common\Freeze\FreezeableInterface;
use Sds\DoctrineExtensions\AccessControl\Behaviour\AccessControlledTrait;
use Sds\DoctrineExtensions\AccessControl\Mapping\Annotation\DoNotAccessControlUpdate as SDS_DoNotAccessControlUpdate;
use Sds\DoctrineExtensions\Freeze\Behaviour\FreezeableTrait;
use Sds\DoctrineExtensions\Freeze\Mapping\Annotation\FreezeField as SDS_FreezeField;

/** @ODM\Document */
class AccessControlled implements FreezeableInterface, AccessControlledInterface {

    use FreezeableTrait;
    use AccessControlledTrait;

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
