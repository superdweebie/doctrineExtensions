<?php

namespace Sds\DoctrineExtensions\Test\DojoModel\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\Dojo(
 *     @Sds\ClassName,
 *     @Sds\Discriminator,
 *     @Sds\InheritFrom({
 *         "me/myModule1",
 *         "me/myModule2"
 *     }),
 *     @Sds\ValidatorGroup(
 *         @Sds\Validator(class = "Sds/Test/ClassValidator1"),
 *         @Sds\Validator(class = "Sds/Test/ClassValidator2", options = {"option1" = "a", "option2" = "b"})
 *     )
 * )
 */
class Simple {

    /**
     * @ODM\Id(strategy="UUID")
     * @Sds\Dojo(
     *     @Sds\Metadata({
     *         "inputType" = "hidden"
     *     })
     * )
     */
    protected $id;

    /**
     * @ODM\String
     * @Sds\Dojo(
     *     @Sds\Metadata({
     *         "label" = "NAME",
     *         "tooltip" = "The simple's name",
     *         "description" = "This is a longer description"
     *     }),
     *     @Sds\ValidatorGroup(
     *         @Sds\Required,
     *         @Sds\Validator(class = "Sds/Test/NameValidator1"),
     *         @Sds\Validator(class = "Sds/Test/NameValidator2", options = {"option1" = "b", "option2" = "b"})
     *     )
     * )
     */
    protected $name;

    /**
     * @ODM\String
     * @Sds\Dojo(
     *     @Sds\ValidatorGroup(
     *         @Sds\Validator(class = "Sds/Test/CountryValidator1")
     *     )
     * )
     */
    protected $country;

    /**
     * @ODM\String
     */
    protected $camelCaseProperty;

    /**
     * @ODM\String
     * @Sds\Dojo(@Sds\Ignore)
     */
    protected $ignoreProperty;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = (string) $name;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    public function getCamelCaseProperty() {
        return $this->camelCaseProperty;
    }

    public function setCamelCaseProperty($camelCaseProperty) {
        $this->camelCaseProperty = $camelCaseProperty;
    }

    public function getIgnoreProperty() {
        return $this->ignoreProperty;
    }

    public function setIgnoreProperty($ignoreProperty) {
        $this->ignoreProperty = $ignoreProperty;
    }
}
