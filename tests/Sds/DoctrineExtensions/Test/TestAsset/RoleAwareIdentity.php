<?php

namespace Sds\DoctrineExtensions\Test\TestAsset;

use Sds\Common\Identity\RoleAwareIdentityInterface;
use Sds\DoctrineExtensions\Identity\DataModel\RoleAwareIdentityTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

class RoleAwareIdentity extends Identity implements RoleAwareIdentityInterface {

    use RoleAwareIdentityTrait;
}
