<?php

namespace Sds\DoctrineExtensions;

use Doctrine\Common\Annotations\Reader;

/**
 * Dummy annotation reader.
 *
 */
class DummyReader implements Reader
{
    /**
     * @param \ReflectionClass $class
     * @return mixed
     */
    function getClassAnnotations(\ReflectionClass $class){}

    /**
     * @param \ReflectionClass $class
     * @param string $annotationName
     * @return mixed
     */
    function getClassAnnotation(\ReflectionClass $class, $annotationName){}

    /**
     * @param \ReflectionMethod $method
     * @return mixed
     */
    function getMethodAnnotations(\ReflectionMethod $method){}

    /**
     * @param \ReflectionMethod $method
     * @param string $annotationName
     * @return mixed
     */
    function getMethodAnnotation(\ReflectionMethod $method, $annotationName){}

    /**
     * @param \ReflectionProperty $property
     * @return mixed
     */
    function getPropertyAnnotations(\ReflectionProperty $property){}

    /**
     * @param \ReflectionProperty $property
     * @param string $annotationName
     * @return mixed
     */
    function getPropertyAnnotation(\ReflectionProperty $property, $annotationName){}
}
