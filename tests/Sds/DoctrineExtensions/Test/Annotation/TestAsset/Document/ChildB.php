<?php

namespace Sds\DoctrineExtensions\Test\Annotation\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\DoNotHardDelete(false)
 * @Sds\DojoClassName(false)
 * @Sds\DojoDiscriminator(false)
 * @Sds\SerializeClassName(false)
 * @Sds\SerializeDiscriminator(false)
 * @Sds\Validator(class ="ChildBValidator")
 * @Sds\WorkflowClass("ChildBWorkflow")
 */
class ChildB extends ParentClass {
}
