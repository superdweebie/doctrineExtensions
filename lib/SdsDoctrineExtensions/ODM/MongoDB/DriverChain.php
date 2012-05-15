<?php

namespace SdsDoctrineExtensions\ODM\MongoDB;

use DoctrineMongoODMModule\Doctrine\ODM\MongoDB\DriverChain as MongoModuleDriverChain,
    SdsDoctrineExtensions\ODM\MongoDB\Mapping\Driver\AnnotationDriver,
    Doctrine\ODM\MongoDB\Mapping\Driver\DriverChain as MongoDriverChain;

class DriverChain extends MongoModuleDriverChain
{
	protected $annotationDriverClass = 'SdsDoctrineExtensions\ODM\MongoDB\Mapping\Driver\AnnotationDriver';
    protected $driverChainClass      = 'Doctrine\ODM\MongoDB\Mapping\Driver\DriverChain';
}