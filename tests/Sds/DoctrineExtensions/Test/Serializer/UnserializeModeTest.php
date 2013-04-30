<?php

namespace Sds\DoctrineExtensions\Test\Serializer;

use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Serializer\Serializer;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\User;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Group;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Profile;

class UnserializeModeTest extends BaseTest {

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

    public function testUnserializePatch(){

        $documentManager = $this->documentManager;

        $user = new User();
        $user->setUsername('superdweebie');
        $user->setPassword('secret'); //uses Serialize Ignore annotation
        $user->defineLocation('here');
        $user->addGroup(new Group('groupA'));
        $user->addGroup(new Group('groupB'));
        $user->setProfile(new Profile('Tim', 'Roediger'));

        $documentManager->persist($user);
        $documentManager->flush();
        $id = $user->getId();
        $documentManager->clear();

        $updated = $this->serializer->fromArray(
            [
                '_className' => 'Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\User',
                'id' => $id,
                'location' => 'there'
            ]
        );

        $this->assertEquals('there', $updated->location());
        $this->assertEquals('superdweebie', $updated->getUsername());
        $this->assertEquals('Tim', $updated->getProfile()->getFirstName());

        $documentManager->remove($updated);
        $documentManager->flush();
        $documentManager->clear();
    }

    public function testUnserializeUpdate(){

        $documentManager = $this->documentManager;

        $user = new User();
        $user->setUsername('superdweebie');
        $user->setPassword('secret'); //uses Serialize Ignore annotation
        $user->defineLocation('here');
        $user->addGroup(new Group('groupA'));
        $user->addGroup(new Group('groupB'));
        $user->setProfile(new Profile('Tim', 'Roediger'));

        $documentManager->persist($user);
        $documentManager->flush();
        $id = $user->getId();
        $documentManager->clear();

        $updated = $this->serializer->fromArray(
            [
                '_className' => 'Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\User',
                'id' => $id,
                'location' => 'there'
            ],
            null,
            Serializer::UNSERIALIZE_UPDATE
        );

        $this->assertEquals('there', $updated->location());
        $this->assertEquals(null, $updated->getUsername());
        $this->assertEquals(null, $updated->getProfile());

    }
}