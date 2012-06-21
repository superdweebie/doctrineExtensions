<?php

namespace SdsDoctrineExtensionsTest\Serializer;

use SdsDoctrineExtensionsTest\BaseTest;
use SdsDoctrineExtensions\Serializer\Subscriber\Serializer as SerializerSubscriber;
use SdsDoctrineExtensions\Serializer\Serializer;
use SdsDoctrineExtensionsTest\Serializer\TestAsset\Document\User;
use SdsDoctrineExtensionsTest\Serializer\TestAsset\Document\Group;
use SdsDoctrineExtensionsTest\Serializer\TestAsset\Document\Profile;
use SdsDoctrineExtensionsTest\Serializer\TestAsset\Document\GetMethodError;

class SerializerTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest(array('SdsDoctrineExtensions\Serializer' => null));

        $this->configure(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\Serializer\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }

    public function testSerializer(){

        $user = new User();
        $user->setUsername('superdweebie');
        $user->setPassword('secret'); //uses doNotSerialize annotation
        $user->setLocation('here'); //uses serializeGetter annotation
        $user->addGroup(new Group('groupA'));
        $user->addGroup(new Group('groupB'));
        $user->setProfile(new Profile('Tim', 'Roediger'));

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

        $array = Serializer::toArray($user, $this->documentManager);

        $this->assertEquals($correct, $array);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testSerializeGetterAnnotationError() {

        $errorDoc = new GetMethodError();
        $errorDoc->setName('error');
        Serializer::toArray($errorDoc, $this->documentManager);
    }
}