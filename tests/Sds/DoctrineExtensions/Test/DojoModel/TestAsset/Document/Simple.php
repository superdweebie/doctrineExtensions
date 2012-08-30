<?php

namespace Sds\DoctrineExtensions\Test\DojoModel\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\ClassDojo(
 *     className = true,
 *     discriminator = true,
 *     inheritFrom = {
 *         "me/myModule1",
 *         "me/myModule2"
 *     },
 *     validators = {
 *         @Sds\DojoValidator(module = "Sds\Test\ClassValidator1"),
 *         @Sds\DojoValidator(module = "Sds\Test\ClassValidator2", options = {"option1" = "a", "option2" = "b"})
 *     }
 * )
 */
class Simple {

    /**
     * @ODM\Id(strategy="UUID")
     * @Sds\PropertyDojo(
     *     inputType = "hidden"
     * )
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @Sds\PropertyDojo(
     *     required = true,
     *     title = "NAME",
     *     tooltip = "The simple's name",
     *     description = "This is a longer description",
     *     validators = {
     *         @Sds\DojoValidator(module = "Sds\Test\NameValidator1"),
     *         @Sds\DojoValidator(module = "Sds\Test\NameValidator2", options = {"option1" = "b", "option2" = "b"})
     *     }
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
        $this->name = (string) $name;
    }
}
