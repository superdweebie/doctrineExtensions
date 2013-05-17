<?php

namespace Sds\DoctrineExtensions\Test\TestAsset;

use Sds\Common\Identity\IdentityInterface;
use Sds\DoctrineExtensions\Identity\DataModel\IdentityTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

class Identity implements IdentityInterface {

    use IdentityTrait;
}
