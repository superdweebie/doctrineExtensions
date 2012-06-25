<?php

namespace SdsDoctrineExtensionsTest\Zone\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Zone\Mapping\Annotation\ZonesField as SDS_ZonesField;
use SdsDoctrineExtensions\Zone\Behaviour\ZoneAwareTrait;
use SdsCommon\Zone\ZoneAwareInterface;

/** @ODM\Document */
class Simple implements ZoneAwareInterface {

    use ZoneAwareTrait;

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
