<?php

namespace Sds\DoctrineExtensions\Test\Validator\TestAsset;

use Sds\Validator\ValidatorInterface;
use Sds\Validator\ValidatorResult;

class ClassValidator implements ValidatorInterface {

    public function isValid($value) {
        return new ValidatorResult(true, []);
    }
}