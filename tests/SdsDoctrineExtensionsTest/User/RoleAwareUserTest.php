<?php

namespace SdsDoctrineExtensionsTest\User;

use SdsDoctrineExtensionsTest\BaseTest;

class RoleAwareUserTest extends BaseTest {

    public function setUp(){
        parent::setUp();

        $this->configActiveUser(true);
    }

    public function testRoleAddandRemove(){

        $user = $this->activeUser;
        $user->setRoles(array('1', '2'));

        $this->assertEquals(array('1', '2'), $user->getRoles());

        $user->removeRole('2');

        $this->assertEquals(array('1'), $user->getRoles());
    }
}