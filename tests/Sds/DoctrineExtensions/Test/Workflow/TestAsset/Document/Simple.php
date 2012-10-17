<?php

namespace Sds\DoctrineExtensions\Test\Workflow\TestAsset\Document;

use Sds\Common\Workflow\WorkflowAwareInterface;
use Sds\DoctrineExtensions\Workflow\DataModel\WorkflowAwareTrait;

//Annotaion imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\Workflow("Sds\DoctrineExtensions\Test\Workflow\TestAsset\SimpleWorkflow")
 */
class Simple implements WorkflowAwareInterface {

    use WorkflowAwareTrait;

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     */
    protected $name;

    /**
     *
     * @ODM\Int
     */
    protected $numStateChanges;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getNumStateChanges() {
        return $this->numStateChanges;
    }

    public function setNumStateChanges($numStateChanges) {
        $this->numStateChanges = $numStateChanges;
    }
}
