<?php

namespace Sds\DoctrineExtensions\Test\Identity\TestAsset\Document;

use Sds\Common\Identity\CredentialInterface;
use Sds\DoctrineExtensions\Identity\DataModel\CredentialTrait;

//Annotaion imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/** @ODM\Document
 * @Sds\AccessControl ({
 *     @Sds\Permission\Basic(roles="*",     allow={"create", "read", "update"}, deny="update::credential"),
 *     @Sds\Permission\Basic(roles="admin", allow="update::credential"                                   )
 * })
 */
class CredentialTraitDoc implements CredentialInterface {

    use CredentialTrait;

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    public function getId() {
        return $this->id;
    }
}
