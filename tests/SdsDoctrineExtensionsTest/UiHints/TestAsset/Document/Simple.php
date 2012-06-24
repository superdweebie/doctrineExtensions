<?php

namespace SdsDoctrineExtensionsTest\UiHints\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\UiHints\Mapping\Annotation\UiHints as SDS_UiHints;

/** @ODM\Document */
class Simple {

    /**
     * @ODM\Id(strategy="UUID")
     * @SDS_UiHints(
     *     hidden = true
     * )
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @SDS_UiHints(
     *     label = "Simple Name",
     *     width = 20,
     *     tooltip = "Simple document name",
     *     description = "Simple document description"
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
