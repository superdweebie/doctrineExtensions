<?php

namespace Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document;

use Sds\Common\AccessControl\AccessControlledInterface;
use Sds\Common\State\StateAwareInterface;
use Sds\DoctrineExtensions\AccessControl\DataModel\AccessControlledTrait;
use Sds\DoctrineExtensions\State\DataModel\StateAwareTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 */
class StateAware implements AccessControlledInterface, StateAwareInterface {

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
