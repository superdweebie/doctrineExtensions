<?php

namespace SdsDoctrineExtensionsTest\Freeze\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsCommon\AccessControl\AccessControlledInterface;
use SdsCommon\Freeze\FreezeableInterface;
use SdsDoctrineExtensions\AccessControl\Behaviour\AccessControlledTrait;
use SdsDoctrineExtensions\AccessControl\Mapping\Annotation\DoNotAccessControlUpdate as SDS_DoNotAccessControlUpdate;
use SdsDoctrineExtensions\Freeze\Behaviour\FreezeableTrait;
use SdsDoctrineExtensions\Freeze\Mapping\Annotation\FreezeField as SDS_FreezeField;

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
