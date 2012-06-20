<?php

namespace SdsDoctrineExtensionsTest\Stamp\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsDoctrineExtensions\Stamp\Behaviour\StampTrait;
use SdsCommon\Stamp;

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
