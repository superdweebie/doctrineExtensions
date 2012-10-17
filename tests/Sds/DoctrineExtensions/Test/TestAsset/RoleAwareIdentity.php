<?php

namespace Sds\DoctrineExtensions\Test\TestAsset;

use Sds\Common\Identity\RoleAwareIdentityInterface;
use Sds\DoctrineExtensions\Identity\DataModel\RoleAwareIdentityTrait;

class RoleAwareIdentity extends Identity implements RoleAwareIdentityInterface {

    use RoleAwareIdentityTrait;
}
