<?php

namespace Sds\DoctrineExtensions\Test\Identity\TestAsset\Document;

use Sds\Common\Identity\CredentialInterface;
use Sds\DoctrineExtensions\Identity\DataModel\CredentialTrait;

//Annotaion imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/** @ODM\Document
 * @Sds\Permission\Basic(roles="all", allow={"create", "read", "update"})
 * @Sds\Permission\Basic(roles="admin", allow="updateCredential")
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
