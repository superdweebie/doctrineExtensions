<?php

namespace SdsDoctrineExtensionsTest\SoftDelete\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsCommon\AccessControl\AccessControlledInterface;
use SdsCommon\SoftDelete\SoftDeleteableInterface;
use SdsDoctrineExtensions\AccessControl\Behaviour\AccessControlledTrait;
use SdsDoctrineExtensions\AccessControl\Mapping\Annotation\DoNotAccessControlUpdate as SDS_DoNotAccessControlUpdate;
use SdsDoctrineExtensions\SoftDelete\Behaviour\SoftDeleteableTrait;
use SdsDoctrineExtensions\SoftDelete\Mapping\Annotation\SoftDeleteField as SDS_SoftDeleteField;

/** @ODM\Document */
class AccessControlled implements SoftDeleteableInterface, AccessControlledInterface {

    use SoftDeleteableTrait;
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
