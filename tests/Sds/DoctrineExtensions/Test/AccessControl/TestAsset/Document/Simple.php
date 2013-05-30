<?php

namespace Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\AccessControl({
 *     @Sds\Permission\Basic(roles="*",          allow="read"                  ),
 *     @Sds\Permission\Basic(roles="creator",    allow="create",  deny="read"  ),
 *     @Sds\Permission\Basic(roles="reader",     allow="read"                  ),
 *     @Sds\Permission\Basic(roles="updater",    allow="update::*"             ),
 *     @Sds\Permission\Basic(roles="deletor",    allow="delete"                ),
 *     @Sds\Permission\Basic(roles="admin",      allow="*",       deny="delete"),
 *     @Sds\Permission\Basic(roles="superadmin", allow="*"                     )
 * })
 */
class Simple {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
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
        $this->name = (string) $name;
    }
}
