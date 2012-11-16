<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Sds\DoctrineExtensions\Accessor\Accessor;
use Sds\DoctrineExtensions\Exception;

/**
 * Provides static methods for serializing documents
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Serializer {

    const IGNORE_UP = 'up';
    const IGNORE_DOWN = 'down';
    const IGNORE_UP_AND_DOWN = 'up_and_down';
    const IGNORE_NONE = 'none';

    /**
     *
     * @param object $document
     * @param DocumentManager $documentManager
     * @return array
     */
    public static function toArray($document, DocumentManager $documentManager){
        return self::serialize($document, $documentManager);
    }

    /**
     *
     * @param object $document
     * @param DocumentManager $documentManager
     * @return string
     */
    public static function toJson($document, DocumentManager $documentManager){
        return json_encode(self::serialize($document, $documentManager));
    }

    /**
     * Will take an associative array representing a document, and apply the
     * serialization metadata rules to that array.
     *
     * @param array $array
     * @param string $className
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager
     * @return array
     */
    public static function applySerializeMetadataToArray(array $array, $className, DocumentManager $documentManager) {

        $classMetadata = $documentManager->getClassMetadata($className);
        $return = array_merge($array, self::serializeClassNameAndDiscriminator($classMetadata));

        foreach ($classMetadata->fieldMappings as $field=>$mapping){

            if (isset($classMetadata->serializer['fields'][$field]['ignore']) &&
                (
                    $classMetadata->serializer['fields'][$field]['ignore'] == self::IGNORE_DOWN ||
                    $classMetadata->serializer['fields'][$field]['ignore'] == self::IGNORE_UP_AND_DOWN
                )
            ){
                if (isset($return[$field])){
                    unset($return[$field]);
                }
                continue;
            }

            if ( isset($mapping['id']) && $mapping['id'] && isset($array['_id'])){
                $return[$field] = $array['_id'];
                unset($return['_id']);
            }

            if(isset($mapping['embedded'])){
                switch ($mapping['type']){
                    case 'one':
                        $return[$field] = self::applySerializeMetadataToArray(
                            $return[$field],
                            $mapping['targetDocument'],
                            $documentManager
                        );
                        break;
                    case 'many':
                        foreach($return[$field] as $index => $embedArray){
                            $return[$field][$index] = self::applySerializeMetadataToArray(
                                $embedArray,
                                $mapping['targetDocument'],
                                $documentManager
                            );
                        }
                        break;
                }
            }
        }

        return $return;
    }

    protected static function serializeClassNameAndDiscriminator(ClassMetadata $classMetadata) {

        $return = array();

        if (isset($classMetadata->serializer['className']) &&
            $classMetadata->serializer['className']
        ) {
            $return[$classMetadata->serializer['classNameProperty']] = $classMetadata->name;
        }

        if (isset($classMetadata->serializer['discriminator']) &&
            $classMetadata->serializer['discriminator'] &&
            $classMetadata->hasDiscriminator()
        ) {
            $return[$classMetadata->discriminatorField['name']] = $classMetadata->discriminatorValue;
        }

        return $return;
    }

    public static function fieldListUp(ClassMetadata $classMetadata){

        $return = [];

        foreach ($classMetadata->fieldMappings as $field=>$mapping){
            if (isset($classMetadata->serializer['fields'][$field]['ignore']) &&
                (
                    $classMetadata->serializer['fields'][$field]['ignore'] == self::IGNORE_UP ||
                    $classMetadata->serializer['fields'][$field]['ignore'] == self::IGNORE_UP_AND_DOWN
                )
            ){
               continue;
            }

            $return[] = $field;
        }

        return $return;
    }

    public static function fieldListDown(ClassMetadata $classMetadata){

        $return = [];

        foreach ($classMetadata->fieldMappings as $field=>$mapping){
            if (isset($classMetadata->serializer['fields'][$field]['ignore']) &&
                (
                    $classMetadata->serializer['fields'][$field]['ignore'] == self::IGNORE_DOWN ||
                    $classMetadata->serializer['fields'][$field]['ignore'] == self::IGNORE_UP_AND_DOWN
                )
            ){
               continue;
            }

            $return[] = $field;
        }

        return $return;
    }

    /**
     *
     * @param object | array $document
     * @param DocumentManager $documentManager
     * @return array
     * @throws \BadMethodCallException
     */
    protected static function serialize($document, DocumentManager $documentManager){

        $classMetadata = $documentManager->getClassMetadata(get_class($document));
        $return = self::serializeClassNameAndDiscriminator($classMetadata);

        foreach ($classMetadata->fieldMappings as $field=>$mapping){

            if (isset($classMetadata->serializer['fields'][$field]['ignore']) &&
                (
                    $classMetadata->serializer['fields'][$field]['ignore'] == self::IGNORE_DOWN ||
                    $classMetadata->serializer['fields'][$field]['ignore'] == self::IGNORE_UP_AND_DOWN
                )
            ){
               continue;
            }

            $getMethod = Accessor::getGetter($classMetadata, $field, $document);

            if(isset($mapping['embedded'])){
                switch ($mapping['type']){
                    case 'one':
                        $embedDocument = $document->$getMethod();
                        if (isset($embedDocument)) {
                            $return[$field] = self::serialize($embedDocument, $documentManager);
                        }
                        break;
                    case 'many':
                        $return[$field] = array();
                        $embedDocuments = $document->$getMethod();
                        foreach($embedDocuments as $embedDocument){
                            $return[$field][] = self::serialize($embedDocument, $documentManager);
                        }
                        break;
                }
            } else {
                $return[$field] = $document->$getMethod();
            }
        }
        return $return;
    }


    /**
     * This will create a document from the supplied array.
     * WARNING: the constructor of the document will not be called.
     *
     * @param array $data
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager
     * @param string $classNameKey
     * @param string $className
     * @return object
     */
    public static function fromArray(
        array $data,
        DocumentManager $documentManager,
        $classNameKey = '_className',
        $className = null
    ) {
        return self::unserialize($data, $documentManager, $classNameKey, $className);
    }

    /**
     *
     * @param array $data
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager
     * @param string $classNameKey
     * @param string $className
     * @return \Sds\DoctrineExtensions\Serializer\className
     * @throws \Exception
     * @throws \BadMethodCallException
     */
    protected static function unserialize(
        array $data,
        DocumentManager $documentManager,
        $classNameKey = '_className',
        $className = null
    ) {

        if (! isset($className) &&
            ! isset($data[$classNameKey])
        ) {
            throw new Exception\InvalidArgumentException(sprintf('Both className and classNameKey %s are not set', $classNameKey));
        }

        $className = isset($data[$classNameKey]) ? $data[$classNameKey] : $className;

        if (! class_exists($className)){
            throw new Exception\ClassNotFoundException(sprintf('ClassName %s could not be loaded', $className));
        }

        $metadata = $documentManager->getClassMetadata($className);

        $reflection = new \ReflectionClass($className);
        $document = $reflection->newInstanceWithoutConstructor();

        foreach ($metadata->fieldMappings as $field=>$mapping){
            if (!isset($data[$field])) {
                continue;
            }

            $setMethod = Accessor::getSetter($metadata, $field, $document);

            if(isset($mapping['embedded'])){
                switch ($mapping['type']){
                    case 'one':
                        $document->$setMethod(self::unserialize(
                            $data[$field],
                            $documentManager,
                            null,
                            $mapping['targetDocument']
                        ));
                        break;
                    case 'many':
                        $collection = array();
                        foreach($data[$field] as $embedData){
                            $collection[] = self::unserialize(
                                $embedData,
                                $documentManager,
                                null,
                                $mapping['targetDocument']
                            );
                        }
                        $document->$setMethod($collection);
                        break;
                }
            } else {
                $document->$setMethod($data[$field]);
            }
        }

        return $document;
    }
}
