<?php

namespace Sds\DoctrineExtensions\Test\Identity;

use Sds\Common\Crypt\Hash;
use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\Identity\TestAsset\Document\CredentialTraitDoc;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\TestAsset\Identity;

class CredentialTraitTest extends BaseTest {

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

    public function testPassword(){

        $password = 'password1';
        $doc = new CredentialTraitDoc();

        $doc->setCredential($password);

        $this->documentManager->persist($doc);
        $this->documentManager->flush();

        $this->assertNotEquals($password, $doc->getCredential());
        $this->assertEquals($doc->getCredential(), Hash::hashCredential($doc, $password));
        $this->assertNotEquals($doc->getCredential(), Hash::hashCredential($doc, 'not password'));

        $newPassword = 'new password';
        $doc->setCredential($newPassword);

        $this->documentManager->flush();

        $this->assertNotEquals($newPassword, $doc->getCredential());
        $this->assertEquals($doc->getCredential(), Hash::hashCredential($doc, $newPassword));
        $this->assertNotEquals($doc->getCredential(), Hash::hashCredential($doc, $password));
    }
}