<?php

namespace SdsDoctrineExtensionsTest\AccessControl\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsCommon\AccessControl\AccessControlledInterface;
use SdsCommon\State\StateAwareInterface;
use SdsDoctrineExtensions\AccessControl\Behaviour\AccessControlledTrait;
use SdsDoctrineExtensions\Audit\Mapping\Annotation\Audit as SDS_Audit;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use SdsDoctrineExtensions\State\Behaviour\StateAwareTrait;
use SdsDoctrineExtensions\State\Mapping\Annotation\StateField as SDS_StateField;

/** @ODM\Document */
class Simple implements AccessControlledInterface, StateAwareInterface {

    use AccessControlledTrait;
    use StateAwareTrait;
    
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
