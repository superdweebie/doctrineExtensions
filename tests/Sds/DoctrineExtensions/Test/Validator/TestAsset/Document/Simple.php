<?php

namespace Sds\DoctrineExtensions\Test\Validator\TestAsset\Document;

//Annotaion imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\ClassValidators({
 *     @Sds\Validator(class = "Sds\DoctrineExtensions\Test\Validator\TestAsset\ClassValidator")
 * })
 */
class Simple {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @Sds\Required
     * @Sds\PropertyValidators({
     *     @Sds\Validator(class = "Sds\DoctrineExtensions\Test\Validator\TestAsset\PropertyValidator1"),
     *     @Sds\Validator(class = "Sds\DoctrineExtensions\Test\Validator\TestAsset\PropertyValidator2")
     * })
     */
    protected $name;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}
