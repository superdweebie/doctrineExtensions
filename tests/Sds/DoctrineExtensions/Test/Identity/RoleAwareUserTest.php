<?php

namespace Sds\DoctrineExtensions\Test\User;

use Sds\DoctrineExtensions\Test\BaseTest;

class RoleAwareUserTest extends BaseTest {

    public function setUp(){
        parent::setUp();

        $this->configIdentity(true);
    }

    public function testRoleAddandRemove(){

        $identity = $this->identity;
        $identity->setRoles(array('1', '2'));

        $this->assertEquals(array('1', '2'), $identity->getRoles());

        $identity->removeRole('2');

        $this->assertEquals(array('1'), $identity->getRoles());
    }
}