<?php

namespace Sds\DoctrineExtensions\Test\Identity\TestAsset\Document;

use Sds\Common\Identity\CredentialInterface;
use Sds\DoctrineExtensions\Identity\DataModel\CredentialTrait;

//Annotaion imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/** @ODM\Document */
class CredentialTraitDoc implements CredentialInterface {

    use CredentialTrait;

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

}
