<?php

namespace Sds\DoctrineExtensions\Identity\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

trait IdentityTrait {

    /**
     * @ODM\String
     * @ODM\Index(unique = true, order = "asc")
     * @Sds\Readonly
     * @Sds\ValidatorGroup(
     *     @Sds\Required,
     *     @Sds\Validator(class = "Sds\Common\Validator\IdentifierValidator")
     * )
     * @Sds\Dojo(
     *     @Sds\Metadata({
     *         "label" = "Username:"
     *     }),
     *     @Sds\ValidatorGroup(
     *         @Sds\Required,
     *         @Sds\Validator(class = "Sds/Common/Validator/IdentifierValidator")
     *     )
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

