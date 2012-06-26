<?php

namespace SdsDoctrineExtensionsTest\TestAsset;

use SdsCommon\User\UserInterface;
use SdsDoctrineExtensions\User\Behaviour\UserTrait;

class User implements UserInterface {

    use UserTrait;
}
