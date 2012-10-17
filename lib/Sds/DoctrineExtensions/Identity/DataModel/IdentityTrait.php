<?php

namespace Sds\DoctrineExtensions\Identity\DataModel;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;

trait IdentityTrait {

    /**
     * @ODM\Field(type="string")
     * @ODM\Index(unique = true, order = "asc")
     * @Sds\Readonly
     * @Sds\ValidatorGroup(
     *     @Sds\Required,
     *     @Sds\Validator(class = "Sds\Common\Validator\IdentifierValidator")
     * )
     * @Sds\Dojo(
     *     @Sds\ValidatorGroup(
     *         @Sds\Required,
     *         @Sds\Validator(class = "Sds/Common/Validator/IdentifierValidator")
     *     )
     * )
     */
    protected $name;

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = (string) $name;
    }
}

