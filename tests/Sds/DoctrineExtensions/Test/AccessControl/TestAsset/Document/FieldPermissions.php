<?php

namespace Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\AccessControl({
 *     @Sds\Permission\Basic(roles="*",          allow={"read", "create"}                      ),
 *     @Sds\Permission\Basic(roles="updater",    allow="update::*",         deny="update::name"),
 *     @Sds\Permission\Basic(roles="admin",      allow="update::name"                          ),
 *     @Sds\Permission\Basic(roles="superadmin", allow="update::*"                             )
 * })
 */
class FieldPermissions {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\String
     */
    protected $name;

    /**
     * @ODM\String
     */
    protected $address;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = (string) $name;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }
}
