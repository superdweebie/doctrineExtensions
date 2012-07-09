<?php

namespace Sds\DoctrineExtensions\Test\DoNotHardDelete\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\DoNotHardDelete\Mapping\Annotation\DoNotHardDelete as SDS_DoNotHardDelete;

/**
 * @ODM\Document
 * @SDS_DoNotHardDelete
 */
class NotDeleteable {

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