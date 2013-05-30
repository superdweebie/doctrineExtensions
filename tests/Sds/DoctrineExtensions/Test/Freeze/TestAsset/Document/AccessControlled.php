<?php

namespace Sds\DoctrineExtensions\Test\Freeze\TestAsset\Document;

use Sds\DoctrineExtensions\Freeze\DataModel\FreezeableTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\AccessControl({
 *     @Sds\Permission\Basic(roles="*",               allow={"create", "read"}),
 *     @Sds\Permission\Basic(roles={"user", "admin"}, allow="freeze"          ),
 *     @Sds\Permission\Basic(roles="admin",           allow="thaw"            )
 * })
 */
class AccessControlled {

    use FreezeableTrait;

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
