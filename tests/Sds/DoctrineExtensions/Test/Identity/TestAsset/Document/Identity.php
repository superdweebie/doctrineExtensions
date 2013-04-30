<?php

namespace Sds\DoctrineExtensions\Test\Identity\TestAsset\Document;

use Sds\Common\Identity\RoleAwareIdentityInterface;
use Sds\DoctrineExtensions\Identity\DataModel\RoleAwareIdentityTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\Permission\Basic(roles="all", allow={"create", "read"})
 * @Sds\Permission\Basic(roles="admin", allow="updateRoles")
 */
class Identity implements RoleAwareIdentityInterface {

    use RoleAwareIdentityTrait;

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
