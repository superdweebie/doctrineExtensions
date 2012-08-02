<?php

namespace Sds\DoctrineExtensions\Test\User\TestAsset\Document;

use Sds\Common\User\AuthInterface;
use Sds\DoctrineExtensions\User\Behaviour\AuthTrait;

//Annotaion imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/** @ODM\Document */
class AuthTraitDoc implements AuthInterface {

    use AuthTrait;
    
    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;
    
}
