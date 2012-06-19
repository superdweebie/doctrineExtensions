<?php

$file = __DIR__.'/../vendor/autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}

require_once $file;

use Doctrine\Common\ClassLoader;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

$classLoader = new ClassLoader('SdsDoctrineExtensionsTest', __DIR__);
$classLoader->register();

AnnotationDriver::registerAnnotationClasses();