<?php

namespace Sds\DoctrineExtensions\Test\Readonly\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/** @ODM\Document */
class SetMethod {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @Sds\Readonly
     * @Sds\Setter("good")
     */
    protected $goodField;

    /**
     * @ODM\Field(type="string")
     * @Sds\Readonly
     * @Sds\Setter("broken")
     */
    protected $badField;

    public function getId() {
        return $this->id;
    }

    public function getGoodField() {
        return $this->goodField;
    }

    public function good($goodField) {
        $this->goodField = $goodField;
    }

    public function getBadField() {
        return $this->badField;
    }

    public function bad($badField) {
        $this->badField = $badField;
    }
}
