<?php

namespace SdsDoctrineExtensionsTest\Zone\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Zone\Mapping\Annotation\ZonesField as SDS_ZonesField;
use SdsDoctrineExtensions\Zone\Behaviour\ZoneAwareObjectTrait;
use SdsCommon\Zone\ZoneAwareObjectInterface;

/** @ODM\Document */
class Simple implements ZoneAwareObjectInterface {

    use ZoneAwareObjectTrait;

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
