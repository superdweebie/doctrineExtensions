<?php

namespace Sds\DoctrineExtensions\Test\TestAsset;

use Sds\Common\User\UserInterface;
use Sds\DoctrineExtensions\User\Behaviour\UserTrait;

class User implements UserInterface {

    use UserTrait;
}
