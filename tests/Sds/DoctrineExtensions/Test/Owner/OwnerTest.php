<?php

namespace Sds\DoctrineExtensions\Test\Owner;

use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\Owner\TestAsset\Document\OwnerDoc;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\TestAsset\Identity;

class OwnerTraitTest extends BaseTest {

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.owner' => true
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

    public function testOwner(){

        $doc = new OwnerDoc();
        $this->documentManager->persist($doc);
        $this->documentManager->flush();

        $this->assertEquals('toby', $doc->getOwner());

        $doc->setOwner('bobby');

        $this->documentManager->flush();

        $this->assertEquals('bobby', $doc->getOwner());
    }
}