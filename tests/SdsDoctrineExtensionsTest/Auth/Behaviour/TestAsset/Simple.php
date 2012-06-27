<?php

namespace SdsDoctrineExtensionsTest\Auth\Behaviour\TestAsset;

use SdsCommon\Auth\AuthInterface;
use SdsDoctrineExtensions\Auth\Behaviour\AuthTrait;

class Simple implements AuthInterface {

    use AuthTrait;
}
