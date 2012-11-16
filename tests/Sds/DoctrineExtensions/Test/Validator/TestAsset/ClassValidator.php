<?php

namespace Sds\DoctrineExtensions\Test\Validator\TestAsset;

use Sds\Common\Validator\ValidatorInterface;
use Sds\Common\Validator\ValidatorResult;

class ClassValidator implements ValidatorInterface {

    public function isValid($value) {
        return new ValidatorResult(true, []);
    }
}