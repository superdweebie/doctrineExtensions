<?php

namespace Sds\DoctrineExtensions\Test\State\TestAsset\Document;

use Sds\Common\State\StateAwareInterface;
use Sds\DoctrineExtensions\State\DataModel\StateTrait;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * @ODM\Document
 * @Sds\Permission\State(roles="all", state="published", allow="read")
 * @Sds\Permission\State(roles="writer", state="draft", allow={"create", "update", "read"})
 * @Sds\Permission\Transition(roles="writer", allow="draft->review")
 * @Sds\Permission\State(roles="reviewer", state="review", allow={"update", "read"})
 * @Sds\Permission\Transition(roles="reviewer", allow={"review->draft", "review->published"}, deny="draft->review")
 * @Sds\Permission\Basic(roles="admin", allow="all")
 */
class AccessControlled {

    use StateTrait;

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
