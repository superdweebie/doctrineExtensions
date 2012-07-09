<?php

namespace Sds\DoctrineExtensions\Test\Zone\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Zone\Mapping\Annotation\ZonesField as SDS_ZonesField;
use Sds\DoctrineExtensions\Zone\Behaviour\ZoneAwareTrait;
use Sds\Common\Zone\ZoneAwareInterface;

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
