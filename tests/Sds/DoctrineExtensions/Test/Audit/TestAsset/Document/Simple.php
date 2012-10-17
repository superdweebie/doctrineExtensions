<?php

namespace Sds\DoctrineExtensions\Test\Audit\TestAsset\Document;

use Sds\Common\Audit\AuditedInterface;
use Sds\DoctrineExtensions\Audit\DataModel\AuditedTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/** @ODM\Document */
class Simple implements AuditedInterface {

    use AuditedTrait;

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @Sds\Audit
     */
    protected $name;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = (string) $name;
    }
}
