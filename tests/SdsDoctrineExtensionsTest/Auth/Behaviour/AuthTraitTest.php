<?php

namespace SdsDoctrineExtensionsTest\Auth\Behaviour;

use SdsCommon\Auth\Crypt;
use SdsDoctrineExtensionsTest\Auth\Behaviour\TestAsset\Simple;
use SdsDoctrineExtensionsTest\BaseTest;

class AccessControlTest extends BaseTest {

    public function testIsGuest(){

        $auth = new Simple();
        $auth->setIsGuest(true);

        $this->assertTrue($auth->getIsGuest());

        $auth->setIsGuest(false);

        $this->assertFalse($auth->getIsGuest());
    }

    public function testPassword(){

        $auth = new Simple();

        $auth->setPassword('password');

        $this->assertNotEquals('password', $auth->getPassword());
        $this->assertEquals($auth->getPassword(), Crypt::hashPassword($auth, 'password'));
        $this->assertNotEquals($auth->getPassword(), Crypt::hashPassword($auth, 'not password'));
    }
}