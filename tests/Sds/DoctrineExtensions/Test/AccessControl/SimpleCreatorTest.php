<?php

namespace Sds\DoctrineExtensions\Test\AccessControl;

use Sds\DoctrineExtensions\AccessControl\Events as AccessControlEvents;
use Sds\DoctrineExtensions\Manifest;
use Sds\DoctrineExtensions\Test\AccessControl\TestAsset\Document\Simple;
use Sds\DoctrineExtensions\Test\BaseTest;
use Sds\DoctrineExtensions\Test\TestAsset\RoleAwareIdentity;

class SimpleCreatorTest extends BaseTest {

    protected $calls = array();

    public function setUp(){

        $manifest = new Manifest([
            'documents' => [
                __NAMESPACE__ . '\TestAsset\Document' => __DIR__ . '/TestAsset/Document'
            ],
            'extension_configs' => [
                'extension.accessControl' => true
            ],
            'document_manager' => 'testing.documentmanager',
            'service_manager_config' => [
                'factories' => [
                    'testing.documentmanager' => 'Sds\DoctrineExtensions\Test\TestAsset\DocumentManagerFactory',
                    'identity' => function(){
                        $identity = new RoleAwareIdentity();
                        $identity->setIdentityName('toby')->addRole('creator');
                        return $identity;
                    }
                ]
            ]
       ]);

       $this->documentManager = $manifest->getServiceManager()->get('testing.documentmanager');
    }

    public function testCreateAllow(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::createDenied, $this);

        $testDoc = new Simple();

        $documentManager->persist($testDoc);
        $documentManager->flush();

        $this->assertNotNull($testDoc->getId());
        $this->assertFalse(isset($this->calls[AccessControlEvents::createDenied]));
    }

    public function testUpdateDeny(){

        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::updateDenied, $this);

        $testDoc = new Simple();
        $testDoc->setName('lucy');

        $documentManager->persist($testDoc);
        $documentManager->flush();

        $testDoc->setName('changed');

        $documentManager->flush();

        $this->assertTrue(isset($this->calls[AccessControlEvents::updateDenied]));
        $this->assertEquals('lucy', $testDoc->getName());
    }

    public function testDeleteDeny(){
        $this->calls = array();
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();

        $eventManager->addEventListener(AccessControlEvents::deleteDenied, $this);

        $testDoc = new Simple();
        $testDoc->setName('lucy');

        $documentManager->persist($testDoc);
        $documentManager->flush();

        $documentManager->remove($testDoc);
        $documentManager->flush();

        $this->assertTrue(isset($this->calls[AccessControlEvents::deleteDenied]));
    }

    public function testReadDeny(){

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
        $this->assertNull($toby);
        $miriam = $repository->find($miriam->getId());
        $this->assertNull($miriam);
    }





//
//    public function testReadControlWithState(){
//
//        $documentManager = $this->documentManager;
//
//        $filter = $documentManager->getFilterCollection()->enable('readAccessControl');
//        $filter->setRoles($this->identity->getRoles());
//
//        $toby = new StateAware();
//        $toby->setName('toby');
//        $toby->setState('draft');
//        $toby->setPermissions(array(
//            new Permission(Role::guest, Action::create, 'draft'),
//            new Permission(Role::guest, Action::read, 'draft')
//        ));
//
//        $miriam = new StateAware();
//        $miriam->setName('miriam');
//        $miriam->setState('draft2');
//        $miriam->setPermissions(array(
//            new Permission(Role::guest, Action::create, 'draft2'),
//            new Permission(Role::guest, Action::read, 'draft')
//        ));
//
//        $documentManager->persist($toby);
//        $documentManager->persist($miriam);
//        $documentManager->flush();
//
//        $tobyId = $toby->getId();
//        $miriamId = $miriam->getId();
//
//        $documentManager->clear();
//        $repository = $documentManager->getRepository(get_class($toby));
//
//        $this->assertEquals(array('toby'), $this->getAllNames($repository));
//
//        $documentManager->getFilterCollection()->disable('readAccessControl');
//
//        $miriam = $repository->find($miriamId);
//        $miriam->setState('draft');
//
//        $documentManager->flush();
//        $documentManager->clear();
//
//        $filter = $documentManager->getFilterCollection()->enable('readAccessControl');
//        $filter->setRoles($this->identity->getRoles());
//
//        $this->assertEquals(array('miriam', 'toby'), $this->getAllNames($repository));
//    }
//
//    public function testUpdateControlDeny(){
//        $this->calls = array();
//        $documentManager = $this->documentManager;
//        $eventManager = $documentManager->getEventManager();
//
//        $eventManager->addEventListener(AccessControlEvents::updateDenied, $this);
//
//        $testDoc = new Simple();
//        $testDoc->setName('lucy');
//        $testDoc->setPermissions(array(
//            new Permission(Role::guest, Action::create),
//            new Permission(Role::user, Action::update)
//        ));
//
//        $documentManager->persist($testDoc);
//        $documentManager->flush();
//        $id = $testDoc->getId();
//        $documentManager->clear();
//        $repository = $documentManager->getRepository(get_class($testDoc));
//
//        $testDoc = $repository->find($id);
//        $testDoc->setName('changed');
//
//        $documentManager->flush();
//
//        $this->assertTrue(isset($this->calls[AccessControlEvents::updateDenied]));
//
//        $documentManager->clear();
//        $testDoc = $repository->find($id);
//
//        $this->assertEquals('lucy', $testDoc->getName());
//    }
//
//    public function testUpdateControlGrant(){
//        $this->calls = array();
//        $documentManager = $this->documentManager;
//        $eventManager = $documentManager->getEventManager();
//
//        $eventManager->addEventListener(AccessControlEvents::updateDenied, $this);
//
//        $testDoc = new Simple();
//        $testDoc->setName('lucy');
//        $testDoc->setPermissions(array(
//            new Permission(Role::guest, Action::create),
//            new Permission(Role::guest, Action::update)
//        ));
//
//        $documentManager->persist($testDoc);
//        $documentManager->flush();
//        $id = $testDoc->getId();
//        $documentManager->clear();
//        $repository = $documentManager->getRepository(get_class($testDoc));
//
//        $testDoc = $repository->find($id);
//        $testDoc->setName('changed');
//
//        $documentManager->flush();
//
//        $this->assertFalse(isset($this->calls[AccessControlEvents::updateDenied]));
//
//        $documentManager->clear();
//        $testDoc = $repository->find($id);
//
//        $this->assertEquals('changed', $testDoc->getName());
//    }
//
//    public function testDeleteControlDeny(){
//        $this->calls = array();
//        $documentManager = $this->documentManager;
//        $eventManager = $documentManager->getEventManager();
//
//        $eventManager->addEventListener(AccessControlEvents::deleteDenied, $this);
//
//        $testDoc = new Simple();
//        $testDoc->setName('lucy');
//        $testDoc->setPermissions(array(
//            new Permission(Role::guest, Action::create),
//            new Permission(Role::user, Action::delete)
//        ));
//
//        $documentManager->persist($testDoc);
//        $documentManager->flush();
//        $id = $testDoc->getId();
//        $documentManager->clear();
//        $repository = $documentManager->getRepository(get_class($testDoc));
//
//        $testDoc = $repository->find($id);
//        $documentManager->remove($testDoc);
//
//        $documentManager->flush();
//
//        $this->assertTrue(isset($this->calls[AccessControlEvents::deleteDenied]));
//
//        $documentManager->clear();
//        $testDoc = $repository->find($id);
//
//        $this->assertEquals('lucy', $testDoc->getName());
//    }
//
//    public function testDeleteControlGrant(){
//        $this->calls = array();
//        $documentManager = $this->documentManager;
//        $eventManager = $documentManager->getEventManager();
//
//        $eventManager->addEventListener(AccessControlEvents::deleteDenied, $this);
//
//        $testDoc = new Simple();
//        $testDoc->setName('lucy');
//        $testDoc->setPermissions(array(
//            new Permission(Role::guest, Action::create),
//            new Permission(Role::guest, Action::delete)
//        ));
//
//        $documentManager->persist($testDoc);
//        $documentManager->flush();
//        $id = $testDoc->getId();
//        $documentManager->clear();
//        $repository = $documentManager->getRepository(get_class($testDoc));
//
//        $testDoc = $repository->find($id);
//        $documentManager->remove($testDoc);
//
//        $documentManager->flush();
//
//        $this->assertFalse(isset($this->calls[AccessControlEvents::deleteDenied]));
//
//        $documentManager->clear();
//        $testDoc = $repository->find($id);
//
//        $this->assertNull($testDoc);
//    }

    protected function getAllNames($repository){
        $names = array();
        $documents = $repository->findAll();
        foreach ($documents as $document){
            $names[] = $document->getName();
        }
        sort($names);
        return $names;
    }

    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}