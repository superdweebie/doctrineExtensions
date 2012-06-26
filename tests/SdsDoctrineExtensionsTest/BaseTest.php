<?php

namespace SdsDoctrineExtensionsTest;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\EventManager;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\Mapping\Driver\DriverChain;
use SdsDoctrineExtensions\Manifest;
use SdsDoctrineExtensions\ManifestConfig;
use SdsDoctrineExtensionsTest\TestAsset\RoleAwareUser;
use SdsDoctrineExtensionsTest\TestAsset\User;

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{

    const DEFAULT_DB = 'sds_doctrine_extensions_tests';

    protected $documentManager;
    
    protected $unitOfWork;
    
    protected $annotationReader;
    
    protected $activeUser;

    public function setUp(){
        $this->annotationReader = new AnnotationReader();
    }

    protected function configActiveUser($configRoleAwareUser = false){
        $user = $configRoleAwareUser ? new RoleAwareUser() : new User();
        $user->setUsername('toby');
        $this->activeUser = $user; 
    }
    
    protected function getManifest(array $extensionConfigs){

        $manifestConfig = new ManifestConfig(
            $this->annotationReader,
            $extensionConfigs,
            $this->activeUser
        );

        return new Manifest($manifestConfig);
    }
    
    protected function configureDoctrine(
        array $documents = array(),
        array $filters = array(),
        array $subscribers = array(),
        array $annotations = array()
    ){
                    
        $config = new Configuration();

        $config->setProxyDir(__DIR__ . '/../Proxies');
        $config->setProxyNamespace('Proxies');

        $config->setHydratorDir(__DIR__ . '/../Hydrators');
        $config->setHydratorNamespace('Hydrators');

        $config->setDefaultDB(self::DEFAULT_DB);

        //create driver chain
        $chain  = new DriverChain;

        foreach ($documents as $namespace => $path){
            $driver = new AnnotationDriver($this->annotationReader, $path);
            $chain->addDriver($driver, $namespace);
        }
        $config->setMetadataDriverImpl($chain);

        //register filters
        foreach ($filters as $name => $class){
            $config->addFilter($name, $class);
        }

        //create event manager
        $eventManager = new EventManager();
        foreach($subscribers as $subscriber){
            $eventManager->addEventSubscriber($subscriber);
        }

        //register annotations
        AnnotationRegistry::registerAutoloadNamespaces($annotations);

        $conn = new Connection(null, array(), $config);
        $this->documentManager = DocumentManager::create($conn, $config, $eventManager);
        $this->unitOfWork = $this->documentManager->getUnitOfWork();
    }

    public function tearDown()
    {
        if ($this->documentManager) {
            $collections = $this->documentManager->getConnection()->selectDatabase(self::DEFAULT_DB)->listCollections();
            foreach ($collections as $collection) {
                $collection->remove(array(), array('safe' => true));
            }
        }
    }
}