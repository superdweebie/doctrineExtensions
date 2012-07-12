<?php

namespace Sds\DoctrineExtensions\Test\UiHints\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/** @ODM\Document */
class Simple {

    /**
     * @ODM\Id(strategy="UUID")
     * @Sds\UiHints(
     *     hidden = true
     * )
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @Sds\UiHints(
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
