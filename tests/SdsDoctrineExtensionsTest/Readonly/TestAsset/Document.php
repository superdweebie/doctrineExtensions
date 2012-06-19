<?php

namespace SdsDoctrineExtensionsTest\Readonly\TestAsset;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;

/** @ODM\Document */
class TestDoc {

    /**
     * @ODM\Field(type="string")
     * @SDS_Readonly
     */
    protected $readonlyField;

    /**
     * @ODM\Field(type="string")
     * @SDS_Readonly
     */
    protected $mutableField;

    public function getReadonlyField() {
        return $this->readonlyField;
    }

    public function setReadonlyField($readonlyField) {
        $this->readonlyField = $readonlyField;
    }

    public function getMutableField() {
        return $this->mutableField;
    }

    public function setMutableField($mutableField) {
        $this->mutableField = $mutableField;
    }
}
