<?php

namespace Sds\DoctrineExtensions\Test\Identity\TestAsset\Document;

use Sds\DoctrineExtensions\Test\TestAsset\RoleAwareIdentity;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\Permission\Basic(roles="all", allow={"create", "read", "update"})
 * @Sds\Permission\Basic(roles="admin", allow="updateRoles")
 */
class Identity extends RoleAwareIdentity {

}
