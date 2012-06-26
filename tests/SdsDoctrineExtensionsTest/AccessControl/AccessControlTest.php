<?php

namespace SdsDoctrineExtensionsTest\AccessControl;

use SdsDoctrineExtensions\AccessControl\Event\Events as AccessControlEvents;
use SdsDoctrineExtensions\AccessControl\Constant\Action;
use SdsDoctrineExtensions\AccessControl\Constant\Role;
use SdsDoctrineExtensions\AccessControl\Model\Permission;
use SdsDoctrineExtensionsTest\AccessControl\TestAsset\Document\Simple;
use SdsDoctrineExtensionsTest\BaseTest;

class AccessControlTest extends BaseTest {

    protected $calls = array();
    
    public function setUp(){

        parent::setUp();
        
        $this->configActiveUser(true);
        
        $manifest = $this->getManifest(array('SdsDoctrineExtensions\AccessControl' => null));

        $this->configureDoctrine(
            array_merge(
                $manifest->getDocuments(),
                array('SdsDoctrineExtensionsTest\AccessControl\TestAsset\Document' => __DIR__ . '/TestAsset/Document')
            ),
            $manifest->getFilters(),
            $manifest->getSubscribers(),
            $manifest->getAnnotations()
        );
    }
    
    public function testReadControlNullState(){
        
        $documentManager = $this->documentManager;
        $eventManager = $documentManager->getEventManager();
        
        $eventManager->addEventListener(AccessControlEvents::createDenied, $this);
        
        $this->activeUser->addRole(Role::guest);
        
        $testDoc = new Simple();
        
        $testDoc->setPermissions(array(
            new Permission(Role::user, Action::create)
        ));
        
        $documentManager->persist($testDoc);
        $documentManager->flush();
        
        $this->assertTrue(isset($this->calls[AccessControlEvents::createDenied]));
    }
    
    public function __call($name, $arguments){
        $this->calls[$name] = $arguments;
    }
}