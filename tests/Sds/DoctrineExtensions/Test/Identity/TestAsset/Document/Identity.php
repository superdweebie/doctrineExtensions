<?php

namespace Sds\DoctrineExtensions\Test\Identity\TestAsset\Document;

use Sds\DoctrineExtensions\Test\TestAsset\RoleAwareIdentity;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\AccessControl ({
 *     @Sds\Permission\Basic(roles="*",     allow={"create", "read", "update"}, deny="update::roles"),
 *     @Sds\Permission\Basic(roles="admin", allow="update::roles"                                   )
 * })
 */
class Identity extends RoleAwareIdentity {

}
