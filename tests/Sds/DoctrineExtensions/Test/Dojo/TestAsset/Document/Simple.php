<?php

namespace Sds\DoctrineExtensions\Test\Dojo\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\Validator(class = "Sds/Test/ClassValidator1"),
 * @Sds\Validator(class = "Sds/Test/ClassValidator2", options = {"option1" = "a", "option2" = "b"})
 * @Sds\Dojo\Model
 * @Sds\Dojo\Form
 * @Sds\Dojo\ModelValidator
 * @Sds\Dojo\JsonRest
 * @Sds\Serializer\ClassName
 */
class Simple {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\String
     * @Sds\Validator\Required,
     * @Sds\Validator(class = "Sds/Test/NameValidator1"),
     * @Sds\Validator(class = "Sds/Test/NameValidator2", options = {"option1" = "b", "option2" = "b"})
     * @Sds\Dojo\Input(
     *     params = {
     *         "label" = "NAME",
     *         "tooltip" = "The document name",
     *         "description" = "This is a longer description"
     *     }
     * )
     */
    protected $name;

    /**
     * @ODM\String
     * @Sds\Validator(class = "Sds/Test/CountryValidator1")
     * @Sds\Dojo\Input(
     *     mixins = {"Sds/Common/Form/ValidationTextarea"}
     *  )
     */
    protected $country;

    /**
     * @ODM\String
     */
    protected $camelCaseField;

    /**
     * @ODM\String
     * @Sds\Serializer\Ignore
     * @Sds\Validator\NotRequired
     */
    protected $ignoreField;

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

    public function getCamelCaseField() {
        return $this->camelCaseField;
    }

    public function setCamelCaseField($camelCaseField) {
        $this->camelCaseField = $camelCaseField;
    }

    public function getIgnoreField() {
        return $this->ignoreField;
    }

    public function setIgnoreField($ignoreField) {
        $this->ignoreField = $ignoreField;
    }
}
