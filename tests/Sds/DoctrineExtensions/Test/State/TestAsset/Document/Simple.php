<?php

namespace Sds\DoctrineExtensions\Test\State\TestAsset\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\Common\State\StateAwareInterface;
use Sds\DoctrineExtensions\AccessControl\Mapping\Annotation\DoNotAccessControlUpdate as SDS_DoNotAccessControlUpdate;
use Sds\DoctrineExtensions\Audit\Mapping\Annotation\Audit as SDS_Audit;
use Sds\DoctrineExtensions\State\Behaviour\StateAwareTrait;
use Sds\DoctrineExtensions\State\Mapping\Annotation\StateField as SDS_StateField;

/** @ODM\Document */
class Simple implements StateAwareInterface {

    use StateAwareTrait;

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
