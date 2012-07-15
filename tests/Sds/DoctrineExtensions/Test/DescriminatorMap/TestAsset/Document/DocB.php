<?php

namespace Sds\DoctrineExtensions\Test\DescriminatorMap\TestAsset\Document;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document(collection="test")
 * @ODM\DiscriminatorField(fieldName="type")
 * @Sds\DiscriminatorMap("Sds\DoctrineExtensions\Test\DescriminatorMap\TestAsset\DescriminatorMap")
 */
class DocB {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @Sds\Audit
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
