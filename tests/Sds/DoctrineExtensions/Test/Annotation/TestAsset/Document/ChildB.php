<?php

namespace Sds\DoctrineExtensions\Test\Annotation\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\DoNotHardDelete(false)
 * @Sds\Serializer(
 *     @Sds\ClassName(false),
 *     @Sds\Discriminator(false)
 * )
 * @Sds\Validator(class = "ParentValidator", value = false)
 * @Sds\Validator(class ="ChildBValidator")
 * @Sds\Workflow("ChildBWorkflow")
 */
class ChildB extends ParentClass {

    /**
     * @Sds\Serializer(@Sds\Ignore("none"))
     */
    protected $name;
}
