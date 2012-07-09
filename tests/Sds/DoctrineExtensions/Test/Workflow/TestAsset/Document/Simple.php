<?php

namespace Sds\DoctrineExtensions\Test\Workflow\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\Common\Workflow\WorkflowAwareInterface;
use Sds\DoctrineExtensions\AccessControl\Mapping\Annotation\DoNotAccessControlUpdate as SDS_DoNotAccessControlUpdate;
use Sds\DoctrineExtensions\Audit\Mapping\Annotation\Audit as SDS_Audit;
use Sds\DoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;
use Sds\DoctrineExtensions\State\Mapping\Annotation\StateField as SDS_StateField;
use Sds\DoctrineExtensions\Workflow\Behaviour\WorkflowAwareTrait;

/** @ODM\Document */
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
