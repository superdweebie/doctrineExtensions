<?php

namespace Sds\DoctrineExtensions\Test\Validator\TestAsset;

use Sds\Common\Validator\ValidatorInterface;

class ClassValidator implements ValidatorInterface {

    protected $messages;

    public function isValid($value) {
        $this->messages = array();

        return true;
    }

    public function getMessages() {
        return $this->messages;
    }
}