<?php

namespace Sds\DoctrineExtensions\Test\Zone\TestAsset\Document;

use Sds\Common\Zone\ZoneAwareInterface;
use Sds\DoctrineExtensions\Zone\DataModel\ZoneAwareTrait;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/** @ODM\Document */
class Simple implements ZoneAwareInterface {

    use ZoneAwareTrait;

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\String
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
