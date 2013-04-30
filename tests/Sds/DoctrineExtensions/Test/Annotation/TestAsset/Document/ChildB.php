<?php

namespace Sds\DoctrineExtensions\Test\Annotation\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\Serializer\ClassName(false)
 * @Sds\Serializer\Discriminator(false)
 * @Sds\Validator(class = "ParentValidator", value = false)
 * @Sds\Validator(class ="ChildBValidator")
 */
class ChildB extends ParentClass {

    /**
     * @Sds\Serializer\Ignore("none")
     */
    protected $name;
}
