<?php

namespace Sds\DoctrineExtensions\Test\Owner\TestAsset\Document;

use Sds\DoctrineExtensions\Owner\DataModel\OwnerTrait;

//Annotaion imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\AccessControl({
 *     @Sds\Permission\Basic(roles="*",     allow={"create", "read"}                     ),
 *     @Sds\Permission\Basic(roles="owner", allow="update::*",       deny="update::owner"),
 *     @Sds\Permission\Basic(roles="admin", allow="update::owner"                        )
 * })
 */
class OwnerDoc {

    use OwnerTrait;

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     *
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
