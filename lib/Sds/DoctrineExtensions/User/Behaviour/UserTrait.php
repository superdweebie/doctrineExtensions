<?php

namespace Sds\DoctrineExtensions\User\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;

trait UserTrait {

    /**
     * @ODM\Field(type="string")
     * @ODM\Index(unique = true, order = "asc")
     * @Sds\Readonly
     * @Sds\Required
     * @Sds\PropertyValidators({
     *     @Sds\Validator(class = "Sds\Common\Validator\IdentifierValidator")
     * })
     * @Sds\PropertyDojo(
     *     required = true,
     *     validators = {
     *         @Sds\DojoValidator(module = "Sds/Validator/IdentifierValidator")
     *     }
     * )
     */
    protected $username;

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = (string) $username;
    }
}

