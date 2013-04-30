<?php

namespace Sds\DoctrineExtensions\Test\Serializer;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\User;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Group;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Profile;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\HasDiscriminator;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\ClassName;

class SerializerTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest(['extensionConfigs' => ['Sds\DoctrineExtensions\Serializer' => true]]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();
        $this->serializer = $manifest->getServiceManager()->get('serializer');
    }

    public function testSerializer(){

        $user = new User();
        $user->setUsername('superdweebie');
        $user->setPassword('secret'); //uses Serialize Ignore annotation
        $user->defineLocation('here');
        $user->addGroup(new Group('groupA'));
        $user->addGroup(new Group('groupB'));
        $user->setProfile(new Profile('Tim', 'Roediger'));

        $correct = array(
            'username' => 'superdweebie',
            'location' => 'here',
            'groups' => array(
                array('name' => 'groupA'),
                array('name' => 'groupB'),
            ),
            'profile' => array(
                'firstname' => 'Tim',
                'lastname' => 'Roediger'
            ),
        );

        $array = $this->serializer->toArray($user);

        $this->assertEquals($correct, $array);
    }

    public function testApplySerializeMetadataToArray(){

        $array = array(
            'id' => null,
            'username' => 'superdweebie',
            'location' => 'here',
            'groups' => array(
                array('name' => 'groupA'),
                array('name' => 'groupB'),
            ),
            'password' => 'secret',
            'profile' => array(
                'firstname' => 'Tim',
                'lastname' => 'Roediger'
            ),
        );

        $correct = array(
            'id' => null,
            'username' => 'superdweebie',
            'location' => 'here',
            'groups' => array(
                array('name' => 'groupA'),
                array('name' => 'groupB'),
            ),
            'profile' => array(
                'firstname' => 'Tim',
                'lastname' => 'Roediger'
            ),
        );

        $array = $this->serializer->ApplySerializeMetadataToArray(
            $array,
            'Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\User'
        );

        $this->assertEquals($correct, $array);
    }

    public function testSerializeDiscriminator() {

        $testDoc = new HasDiscriminator();
        $testDoc->setName('superdweebie');

        $correct = array(
            'type' => 'hasDiscriminator',
            'name' => 'superdweebie',
        );

        $array = $this->serializer->toArray($testDoc);

        $this->assertEquals($correct, $array);
    }

    public function testSerializeClassName() {

        $testDoc = new ClassName();
        $testDoc->setName('superdweebie');

        $correct = array(
            '_className' => 'Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\ClassName',
            'name' => 'superdweebie',
        );

        $array = $this->serializer->toArray($testDoc);

        $this->assertEquals($correct, $array);
    }

    public function testUnserializer(){

        $data = array(
            'id' => 1234567890,
            'username' => 'superdweebie',
            'password' => 'testIgnore',
            'location' => 'here',
            'groups' => array(
                array('name' => 'groupA'),
                array('name' => 'groupB'),
            ),
            'profile' => array(
                'firstname' => 'Tim',
                'lastname' => 'Roediger'
            ),
        );

        $user = $this->serializer->fromArray(
            $data,
            'Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\User'
        );

        $this->assertTrue($user instanceof User);
        $this->assertEquals(1234567890, $user->getId());
        $this->assertEquals('superdweebie', $user->getUsername());
        $this->assertEquals(null, $user->getPassword());
        $this->assertEquals('here', $user->location());
        $this->assertEquals('groupA', $user->getGroups()[0]->getName());
        $this->assertEquals('groupB', $user->getGroups()[1]->getName());
        $this->assertEquals('Tim', $user->getProfile()->getFirstname());
        $this->assertEquals('Roediger', $user->getProfile()->getLastname());
    }

    public function testUnserializeClassName() {

        $data = array(
            '_className' => 'Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\ClassName',
            'id' => null,
            'name' => 'superdweebie',
        );

        $testDoc = $this->serializer->fromArray($data);

        $this->assertTrue($testDoc instanceof ClassName);
        $this->assertEquals('superdweebie', $testDoc->getName());
    }
}