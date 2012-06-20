<?php

namespace SdsDoctrineExtensionsTest\Stamp\TestAsset;

use SdsCommon\User\UserInterface;

class User implements UserInterface {

    protected $username;

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function isGuest() {
    }

    public function setIsGuest($isGuest){
    }
}
