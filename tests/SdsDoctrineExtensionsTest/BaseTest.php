<?php

namespace SdsDoctrineExtensionsTest;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\Mapping\Driver\DriverChain;
use Doctrine\Common\EventManager;
use Doctrine\MongoDB\Connection;

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{

    const DEFAULT_DB = 'sds_doctrine_extensions_tests';

    protected $dm;
    protected $uow;

    protected function configure(
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
        $reader = new AnnotationReader();
        foreach ($documents as $namespace => $path){
            $driver = new AnnotationDriver($reader, $path);
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
        foreach ($annotations as $annotation){
            AnnotationRegistry::registerFile($annotation);
        }

        $conn = new Connection(null, array(), $config);
        $this->dm = DocumentManager::create($conn, $config);
        $this->uow = $this->dm->getUnitOfWork();
    }

    public function tearDown()
    {
        if ($this->dm) {
            $collections = $this->dm->getConnection()->selectDatabase(self::DEFAULT_DB)->listCollections();
            foreach ($collections as $collection) {
                $collection->remove(array(), array('safe' => true));
            }
        }
    }
}