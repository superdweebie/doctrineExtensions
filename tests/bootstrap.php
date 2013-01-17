<?php

$loaderPath = 'vendor/autoload.php';

// Root if testing independently
$applicationRoot = __DIR__ . '/../';

if ( ! file_exists($applicationRoot . $loaderPath )) {
    // Root if testing as part of a larger app
    $applicationRoot = __DIR__ . '/../../../../';
}

chdir($applicationRoot);

$loader = require_once($loaderPath);
$loader->add('Sds\DoctrineExtensions\Test', __DIR__);

use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

AnnotationDriver::registerAnnotationClasses();
