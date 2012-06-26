<?php

namespace SdsDoctrineExtensionsTest\TestAsset;

use SdsCommon\User\RoleAwareUserInterface;
use SdsDoctrineExtensions\User\Behaviour\RoleAwareUserTrait;

class RoleAwareUser implements RoleAwareUserInterface {

    use RoleAwareUserTrait;
}
