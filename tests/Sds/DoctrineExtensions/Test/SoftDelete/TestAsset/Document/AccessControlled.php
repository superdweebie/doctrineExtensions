<?php

namespace Sds\DoctrineExtensions\Test\SoftDelete\TestAsset\Document;

use Sds\DoctrineExtensions\SoftDelete\DataModel\SoftDeleteableTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\AccessControl({
 *     @Sds\Permission\Basic(roles="*",               allow={"create", "read"}),
 *     @Sds\Permission\Basic(roles={"user", "admin"}, allow="softDelete"      ),
 *     @Sds\Permission\Basic(roles="admin",           allow="restore"         )
 * })
 */
class AccessControlled {

    use SoftDeleteableTrait;

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
