<?php

namespace Sds\DoctrineExtensions\Test\AccessControl;

use Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document\Simple;
use Sds\DoctrineExtensions\Test\BaseTest;

class SimpleReaderCreatorTest extends BaseTest {

    protected $calls = array();

    public function setUp(){
        parent::setUp();

        $this->configIdentity(true);
        $this->identity->addRole('creator');
        $this->identity->addRole('reader');

        $manifest = $this->getManifest(['extensionConfigs' => ['Sds\DoctrineExtensions\AccessControl' => true]]);

        $this->configDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers()
        );
        $manifest->setDocumentManagerService($this->documentManager)->bootstrapped();
    }

    public function testReadControlAllow(){

        $documentManager = $this->documentManager;

        $toby = new Simple();
        $toby->setName('toby');
        $miriam = new Simple();
        $miriam->setName('miriam');
        $documentManager->persist($toby);
        $documentManager->persist($miriam);
        $documentManager->flush();
        $documentManager->clear();

        $repository = $documentManager->getRepository(get_class($toby));

        $toby = $repository->find($toby->getId());
        $this->assertNotNull($toby);
        $miriam = $repository->find($miriam->getId());
        $this->assertNotNull($miriam);
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}