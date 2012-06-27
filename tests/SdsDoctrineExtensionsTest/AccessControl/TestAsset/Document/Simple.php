<?php

namespace SdsDoctrineExtensionsTest\AccessControl\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsCommon\AccessControl\AccessControlledInterface;
use SdsDoctrineExtensions\AccessControl\Behaviour\AccessControlledTrait;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;

/** @ODM\Document */
class Simple implements AccessControlledInterface {

    use AccessControlledTrait;
    
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
        $this->name = (string) $name;
    }
}
