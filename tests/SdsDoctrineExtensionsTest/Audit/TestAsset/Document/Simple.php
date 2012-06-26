<?php

namespace SdsDoctrineExtensionsTest\Audit\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Audit\Mapping\Annotation\Audit as SDS_Audit;
use SdsDoctrineExtensions\Audit\Behaviour\AuditedTrait;
use SdsCommon\Audit\AuditedInterface;

/** @ODM\Document */
class Simple implements AuditedInterface {

    use AuditedTrait;

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @SDS_Audit
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
