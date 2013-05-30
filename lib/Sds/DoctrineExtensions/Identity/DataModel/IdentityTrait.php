<?php

namespace Sds\DoctrineExtensions\Identity\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

trait IdentityTrait {

    /**
     * @ODM\Id(strategy="none")
     * @ODM\Index(unique = true, order = "asc")
     * @Sds\Validator\Required
     * @Sds\Validator\Identifier
     */
    protected $identityName;

    public function getIdentityName() {
        return $this->identityName;
    }

    public function setIdentityName($identityName) {
        $this->identityName = (string) $identityName;
        return $this;
    }
}
