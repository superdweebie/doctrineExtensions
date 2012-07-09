<?php

namespace Sds\DoctrineExtensions\Test\Serializer;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Serializer\Subscriber\Serializer as SerializerSubscriber;
use Sds\DoctrineExtensions\Serializer\Serializer;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\User;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Group;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Profile;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\GetMethodError;

class SerializerTest extends BaseTest {

    public function setUp(){

        parent::setUp();
        $manifest = $this->getManifest(array('Sds\DoctrineExtensions\Serializer' => null));

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
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