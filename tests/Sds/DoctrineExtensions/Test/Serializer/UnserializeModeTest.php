<?php

namespace Sds\DoctrineExtensions\Test\Serializer;

use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Serializer\Serializer;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\User;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Group;
use Sds\DoctrineExtensions\Test\Serializer\TestAsset\Document\Profile;

class UnserializeModeTest extends BaseTest {

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.serializer' => true
            ],
            'document_manager' => 'testing.documentmanager',
            'service_manager_config' => [
                'factories' => [
                    'testing.documentmanager' => 'Sds\DoctrineExtensions\Test\TestAsset\DocumentManagerFactory',
                ]
            ]
        ]);

        $this->documentManager = $manifest->getServiceManager()->get('testing.documentmanager');
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