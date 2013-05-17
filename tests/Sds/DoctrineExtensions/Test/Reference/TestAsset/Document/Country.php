<?php

namespace Sds\DoctrineExtensions\Test\Reference\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 */
class Country {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;
}
