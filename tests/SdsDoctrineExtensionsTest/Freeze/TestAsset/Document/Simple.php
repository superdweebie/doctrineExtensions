<?php

namespace SdsDoctrineExtensionsTest\Freeze\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Freeze\Mapping\Annotation\FreezeField as SDS_FreezeField;
use SdsDoctrineExtensions\Freeze\Behaviour\FreezeableTrait;
use SdsCommon\Freeze\FreezeableInterface;

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
