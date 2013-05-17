<?php

namespace Sds\DoctrineExtensions\Test\User;

use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\TestAsset\RoleAwareIdentity;

class RoleAwareUserTest extends BaseTest {

    public function setUp(){

        $manifest = new Manifest([
            'service_manager_config' => [
                'factories' => [
                    'identity' => function(){
                        $identity = new RoleAwareIdentity();
                        $identity->setIdentityName('toby');
                        return $identity;
                    }
                ]
            ]
        ]);

        $this->identity = $manifest->getServiceManager()->get('identity');
    }

    public function testRoleAddandRemove(){

        $identity = $this->identity;
        $identity->setRoles(array('1', '2'));

        $this->assertEquals(array('1', '2'), $identity->getRoles());

        $identity->removeRole('2');

        $this->assertEquals(array('1'), $identity->getRoles());
    }
}