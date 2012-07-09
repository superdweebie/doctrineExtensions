<?php

namespace Sds\DoctrineExtensions\Test\Auth\Behaviour\TestAsset;

use Sds\Common\Auth\AuthInterface;
use Sds\DoctrineExtensions\Auth\Behaviour\AuthTrait;

class Simple implements AuthInterface {

    use AuthTrait;
}
