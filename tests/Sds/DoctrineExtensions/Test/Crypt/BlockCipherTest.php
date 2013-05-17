<?php

namespace Sds\DoctrineExtensions\Test\Crypt;

use Sds\DoctrineExtensions\Crypt\BlockCipherService;
use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\Crypt\TestAsset\Document\Simple;
use Sds\DoctrineExtensions\Test\TestAsset\Identity;

class BlockCipherTest extends BaseTest {

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.crypt' => true
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

    public function testBlockCipher(){
        $documentManager = $this->documentManager;

        $testDoc = new Simple();
        $testDoc->setName('Toby');

        $documentManager->persist($testDoc);
        $documentManager->flush();

        $id = $testDoc->getId();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($testDoc));
        $testDoc = $repository->find($id);

        $this->assertNotEquals('Toby', $testDoc->getName());

        BlockCipherService::decryptDocument($testDoc, $documentManager->getClassMetadata(get_class($testDoc)));

        $this->assertEquals('Toby', $testDoc->getName());

        $testDoc->setName('Lucy');

        $documentManager->flush();
        $documentManager->clear();

        $testDoc = $repository->find($id);

        $this->assertNotEquals('Lucy', $testDoc->getName());

        BlockCipherService::decryptDocument($testDoc, $documentManager->getClassMetadata(get_class($testDoc)));

        $this->assertEquals('Lucy', $testDoc->getName());
    }
}