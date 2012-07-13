<?php

namespace Sds\DoctrineExtensions\User\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;

trait UserTrait {

    /**
     * @ODM\Field(type="string")
     * @ODM\Index(unique = true, order = "asc")
     * @Sds\Readonly
     * @Sds\UiHints(label = "Username")
     * @Sds\Validator(class = "Sds\DoctrineExtensions\Validator\Validator\StandardName")
     */
    protected $username;

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = (string) $username;
    }
}

