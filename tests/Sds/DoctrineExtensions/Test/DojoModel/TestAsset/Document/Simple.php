<?php

namespace Sds\DoctrineExtensions\Test\DojoModel\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\Validator(class = "Sds/Test/ClassValidator1"),
 * @Sds\Validator(class = "Sds/Test/ClassValidator2", options = {"option1" = "a", "option2" = "b"})
 * @Sds\DojoModel(
 *     @Sds\ClassName,
 *     @Sds\Discriminator,
 *     @Sds\InheritFrom({
 *         "me/myModule1",
 *         "me/myModule2"
 *     }),
 *     @Sds\Mixin({
 *         "property1" = 1,
 *         "property2" = 2
 *     })
 * )
 */
class Simple {

    /**
     * @ODM\Id(strategy="UUID")
     * @Sds\DojoInput(
     *     @Sds\Mixin({
     *         "type" = "hidden"
     *     })
     * )
     */
    protected $id;

    /**
     * @ODM\String
     * @Sds\RequiredValidator,
     * @Sds\Validator(class = "Sds/Test/NameValidator1"),
     * @Sds\Validator(class = "Sds/Test/NameValidator2", options = {"option1" = "b", "option2" = "b"})
     * @Sds\DojoInput(
     *     @Sds\Mixin({
     *         "label" = "NAME",
     *         "tooltip" = "The simple's name",
     *         "description" = "This is a longer description"
     *     })     
     * )
     */
    protected $name;

    /**
     * @ODM\String
     * @Sds\NotRequiredValidator,
     * @Sds\Validator(class = "Sds/Test/CountryValidator1")
     */
    protected $country;

    /**
     * @ODM\String
     */
    protected $camelCaseProperty;

    /**
     * @ODM\String
     * @Sds\DojoModel(@Sds\Ignore)
     * @Sds\DojoInput(@Sds\Ignore)     
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
