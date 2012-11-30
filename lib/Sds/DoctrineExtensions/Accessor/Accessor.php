<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Accessor;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Exception;

/**
 * Helper functions to get property getter and setter methods
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Accessor {

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadata $metadata
     * @param string $field
     * @param object $document
     * @return string
     * @throws Exception\BadMethodCallException
     */
    public static function getGetter(ClassMetadata $metadata, $field, $document){

        if(isset($metadata->fieldMappings[$field]['getter'])
        ){
            $getMethod = $metadata->fieldMappings[$field]['getter'];
        } else {
            $getMethod = 'get'.ucfirst($field);
        }

        if (!method_exists($document, $getMethod)){
            throw new Exception\BadMethodCallException(sprintf(
                'Method %s not found. This method was defined in the @Getter annotation to be used for getting a property',
                $getMethod
            ));
        }

        return $getMethod;
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadata $metadata
     * @param string $field
     * @param object $document
     * @return string
     * @throws Exception\BadMethodCallException
     */
    public static function getSetter(ClassMetadata $metadata, $field, $document){

        if(isset($metadata->fieldMappings[$field]['setter'])
        ){
            $setMethod = $metadata->fieldMappings[$field]['setter'];
        } else {
            $setMethod = 'set'.ucfirst($field);
        }

        if (!method_exists($document, $setMethod)){
            throw new Exception\BadMethodCallException(sprintf(
                'Method %s not found. This method was defined in the @Setter annotation to be used for setting a property',
                $setMethod
            ));
        }

        return $setMethod;
    }
}
