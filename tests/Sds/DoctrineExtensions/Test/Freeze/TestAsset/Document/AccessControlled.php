<?php

namespace Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document;

use Sds\Common\AccessControl\AccessControlledInterface;
use Sds\Common\Freeze\FreezeableInterface;
use Sds\DoctrineExtensions\AccessControl\DataModel\AccessControlledTrait;
use Sds\DoctrineExtensions\Freeze\DataModel\FreezeableTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\AccessControl(
 *     @Sds\AccessControl\DefaultValue(false),
 *     @Sds\AccessControl\Freeze(true),
 *     @Sds\AccessControl\Thaw(true)
 * )
 */
class AccessControlled implements FreezeableInterface, AccessControlledInterface {

    use FreezeableTrait;
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
        $this->name = $name;
    }
}
