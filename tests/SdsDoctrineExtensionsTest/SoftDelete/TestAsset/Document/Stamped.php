<?php

namespace SdsDoctrineExtensionsTest\SoftDelete\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\AccessControl\Mapping\Annotation\DoNotAccessControlUpdate as SDS_DoNotAccessControlUpdate;
use SdsDoctrineExtensions\SoftDelete\Mapping\Annotation\SoftDeleteField as SDS_SoftDeleteField;
use SdsDoctrineExtensions\SoftDelete\Behaviour\SoftStampTrait;
use SdsCommon\SoftDelete\SoftDeletedByInterface;
use SdsCommon\SoftDelete\SoftDeletedOnInterface;
use SdsCommon\SoftDelete\RestoredByInterface;
use SdsCommon\SoftDelete\RestoredOnInterface;
use SdsDoctrineExtensions\SoftDelete\Behaviour\SoftDeleteableTrait;
use SdsCommon\SoftDelete\SoftDeleteableInterface;

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
