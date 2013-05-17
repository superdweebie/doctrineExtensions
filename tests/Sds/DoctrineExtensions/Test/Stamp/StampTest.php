<?php

namespace Sds\DoctrineExtensions\Test\Stamp;

use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Stamp\TestAsset\Document\Simple;
use Sds\DoctrineExtensions\Test\TestAsset\Identity;

class StampTest extends BaseTest {

    protected $subscriber;

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.stamp' => true
            ],
            'document_manager' => 'testing.documentmanager',
            'service_manager_config' => [
                'factories' => [
                    'testing.documentmanager' => 'Sds\DoctrineExtensions\Test\TestAsset\DocumentManagerFactory',
                    'identity' => function(){
                        $identity = new Identity();
                        $identity->setIdentityName('toby');
                        return $identity;
                    }
                ]
            ]
        ]);

        $this->documentManager = $manifest->getServiceManager()->get('testing.documentmanager');
    }

    public function testStamp(){

        $documentManager = $this->documentManager;
        $testDoc = new Simple();
        $testDoc->setName('version1');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('version1', $testDoc->getName());
        $this->assertEquals('toby', $testDoc->getCreatedBy());
        $this->assertNotNull($testDoc->getCreatedOn());
        $this->assertEquals('toby', $testDoc->getUpdatedBy());
        $this->assertNotNull($testDoc->getUpdatedOn());

        $testDoc->setName('version2');

        $documentManager->persist($testDoc);
        $documentManager->flush();
        $id = $testDoc->getId();
        $documentManager->clear();

        $testDoc = null;
        $testDoc = $repository->find($id);

        $this->assertEquals('version2', $testDoc->getName());
        $this->assertEquals('toby', $testDoc->getCreatedBy());
        $this->assertNotNull($testDoc->getCreatedOn());
        $this->assertEquals('toby', $testDoc->getUpdatedBy());
        $this->assertNotNull($testDoc->getUpdatedOn());
    }
}