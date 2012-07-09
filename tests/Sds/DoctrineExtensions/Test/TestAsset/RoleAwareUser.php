<?php

namespace Sds\DoctrineExtensions\Test\TestAsset;

use Sds\Common\User\RoleAwareUserInterface;
use Sds\DoctrineExtensions\User\Behaviour\RoleAwareUserTrait;

class RoleAwareUser implements RoleAwareUserInterface {

    use RoleAwareUserTrait;
}
