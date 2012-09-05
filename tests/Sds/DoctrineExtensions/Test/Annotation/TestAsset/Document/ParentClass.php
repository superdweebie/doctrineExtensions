<?php

namespace Sds\DoctrineExtensions\Test\Annotation\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\DoNotHardDelete
 * @Sds\Serializer(
 *     @Sds\ClassName,
 *     @Sds\Discriminator
 * )
 * @Sds\ValidatorGroup(@Sds\Validator(class = "ParentValidator"))
 * @Sds\WorkflowClass("ParentWorkflow")
 */
class ParentClass {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @Sds\Serializer(@Sds\Ignore)
     */
    protected $name;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = (string) $name;
    }
}
