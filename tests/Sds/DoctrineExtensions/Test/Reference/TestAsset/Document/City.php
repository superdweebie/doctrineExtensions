<?php

namespace Sds\DoctrineExtensions\Test\Reference\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\Rest
 */
class City {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\ReferenceOne(targetDocument="Country", simple="true", cascade="all")
     */
    protected $country;
}
