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
 * @Sds\ValidatorGroup(@Sds\Validator(class ="ChildBValidator"))
 * @Sds\WorkflowClass("ChildBWorkflow")
 */
class ChildB extends ParentClass {

    /**
     * @Sds\Serializer(@Sds\Ignore(false))
     */
    protected $name;
}
