<?php

namespace Sds\DoctrineExtensions\Test\User;

use Sds\Common\Crypt\Hash;
use Sds\DoctrineExtensions\Test\User\TestAsset\Document\AuthTraitDoc;
use Sds\DoctrineExtensions\Test\BaseTest;

class AccessControlTest extends BaseTest {

    public function setUp(){

        parent::setUp();

        $this->configActiveUser();

        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\Crypt' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array(__NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
    }

    public function testIsGuest(){

        $auth = new AuthTraitDoc();
        $auth->setIsGuest(true);

        $this->assertTrue($auth->getIsGuest());

        $auth->setIsGuest(false);

        $this->assertFalse($auth->getIsGuest());
    }

    public function testPassword(){

        $auth = new AuthTraitDoc();

        $auth->setPassword('password');

        $this->documentManager->persist($auth);
        $this->documentManager->flush();

        $this->assertNotEquals('password', $auth->getPassword());
        $this->assertEquals($auth->getPassword(), Hash::hashPassword($auth, 'password'));
        $this->assertNotEquals($auth->getPassword(), Hash::hashPassword($auth, 'not password'));

        $auth->setPassword('new password');

        $this->documentManager->flush();

        $this->assertNotEquals('new password', $auth->getPassword());
        $this->assertEquals($auth->getPassword(), Hash::hashPassword($auth, 'new password'));
        $this->assertNotEquals($auth->getPassword(), Hash::hashPassword($auth, 'password'));
    }
}