<?php

namespace Sds\DoctrineExtensions\Identity\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

trait IdentityTrait {

    /**
     * @ODM\String
     * @ODM\Index(unique = true, order = "asc")
     * @Sds\Readonly
     * @Sds\RequiredValidator
     * @Sds\IdentifierValidator
     *
     * @Sds\DojoInput(
     *     params = {
     *         "label" = "Username:"
     *     }
     * )
     */
    protected $identityName;

    public function getIdentityName() {
        return $this->identityName;
    }

    public function setIdentityName($identityName) {
        $this->identityName = (string) $identityName;
    }
}

