<?php

namespace Sds\DoctrineExtensions\Test\Validator\TestAsset;

use Sds\Common\Validator\ValidatorInterface;

class PropertyValidator1 implements ValidatorInterface {

    protected $messages;

    public function isValid($value) {
        $this->messages = array();

        if ($value == 'valid' || $value == 'alsoValid') {
            return true;
        } else {
            $this->messages[] = 'invalid name 1';
            return false;
        }
    }

    public function getMessages() {
        return $this->messages;
    }
}