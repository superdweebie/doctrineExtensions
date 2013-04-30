<?php

namespace Sds\DoctrineExtensions\Test\Validator\TestAsset\Document;

//Annotaion imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\Validator(class = "Sds\DoctrineExtensions\Test\Validator\TestAsset\ClassValidator")
 */
class Simple {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @Sds\Validator\Required
     * @Sds\Validator(class = "Sds\DoctrineExtensions\Test\Validator\TestAsset\FieldValidator1")
     * @Sds\Validator(class = "Sds\DoctrineExtensions\Test\Validator\TestAsset\FieldValidator2")
     * )
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
