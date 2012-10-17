<?php

namespace Sds\DoctrineExtensions\Test\TestAsset;

use Sds\Common\Identity\IdentityInterface;
use Sds\DoctrineExtensions\Identity\DataModel\IdentityTrait;

class Identity implements IdentityInterface {

    use IdentityTrait;
}
