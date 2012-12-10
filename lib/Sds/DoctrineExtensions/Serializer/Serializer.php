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

    const IGNORE_WHEN_UNSERIALIZING = 'ignore_when_unserializing';
    const IGNORE_WHEN_SERIALIZING = 'ignore_when_serializing';
    const IGNORE_ALWAYS = 'ignore_always';
    const IGNORE_NEVER = 'ignore_never';

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
        $fieldList = self::fieldListForSerialize($classMetadata);
        $return = array_merge($array, self::serializeClassNameAndDiscriminator($classMetadata));

        foreach ($classMetadata->fieldMappings as $field=>$mapping){

            if ( ! in_array($field, $fieldList)){
                if (isset($return[$field])){
                    unset($return[$field]);
                }
                continue;
            }

            if ( isset($mapping['id']) && $mapping['id'] && isset($array['_id'])){
                $return[$field] = $array['_id'];
                unset($return['_id']);
            }

            switch (true){
                case isset($mapping['embedded']) && $mapping['type'] == 'one':
                    $return[$field] = self::applySerializeMetadataToArray(
                        $return[$field],
                        $mapping['targetDocument'],
                        $documentManager
                    );
                    break;
                case isset($mapping['embedded']) && $mapping['type'] == 'many':
                    foreach($return[$field] as $index => $embedArray){
                        $return[$field][$index] = self::applySerializeMetadataToArray(
                            $embedArray,
                            $mapping['targetDocument'],
                            $documentManager
                        );
                    }
                    break;
                case isset($mapping['reference']) && $mapping['type'] == 'one':
                    $referenceSerializer = self::getReferenceSerializer($field, $classMetadata);
                    $return[$field] = $referenceSerializer::serialize(
                        $return[$field]['$id'],
                        $mapping,
                        $documentManager
                    );
                    break;
                case isset($mapping['reference']) && $mapping['type'] == 'many':
                    $referenceSerializer = self::getReferenceSerializer($field, $classMetadata);
                    foreach($return[$field] as $index => $referenceDocument){
                        $return[$field][$index] = $referenceSerializer::serialize(
                            $referenceDocument['$id'],
                            $mapping,
                            $documentManager
                        );
                    }
                    break;
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

    public static function fieldListForUnserialize(ClassMetadata $classMetadata){

        $return = [];

        foreach ($classMetadata->fieldMappings as $field=>$mapping){
            if (isset($classMetadata->serializer['fields'][$field]['ignore']) &&
                (
                    $classMetadata->serializer['fields'][$field]['ignore'] == self::IGNORE_WHEN_UNSERIALIZING ||
                    $classMetadata->serializer['fields'][$field]['ignore'] == self::IGNORE_ALWAYS
                )
            ){
               continue;
            }
            $return[] = $field;
        }

        return $return;
    }

    public static function fieldListForSerialize(ClassMetadata $classMetadata){

        $return = [];

        foreach ($classMetadata->fieldMappings as $field=>$mapping){
            if (isset($classMetadata->serializer['fields'][$field]['ignore']) &&
                (
                    $classMetadata->serializer['fields'][$field]['ignore'] == self::IGNORE_WHEN_SERIALIZING ||
                    $classMetadata->serializer['fields'][$field]['ignore'] == self::IGNORE_ALWAYS
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
        $fieldList = self::fieldListForSerialize($classMetadata);
        $return = self::serializeClassNameAndDiscriminator($classMetadata);

        foreach ($classMetadata->fieldMappings as $field=>$mapping){

            if ( ! in_array($field, $fieldList)){
                continue;
            }

            $getMethod = Accessor::getGetter($classMetadata, $field, $document);

            switch (true){
                case isset($mapping['embedded']) && $mapping['type'] == 'one':
                    $embedDocument = $document->$getMethod();
                    if (isset($embedDocument)) {
                        $return[$field] = self::serialize($embedDocument, $documentManager);
                    }
                    break;
                case isset($mapping['embedded']) && $mapping['type'] == 'many':
                    $return[$field] = array();
                    $embedDocuments = $document->$getMethod();
                    foreach($embedDocuments as $embedDocument){
                        $return[$field][] = self::serialize($embedDocument, $documentManager);
                    }
                    break;
                case isset($mapping['reference']) && $mapping['type'] == 'one':
                    $referenceSerializer = self::getReferenceSerializer($field, $classMetadata);
                    $return[$field] = $referenceSerializer::serialize(
                        $document->$getMethod()->getId(),
                        $mapping,
                        $documentManager
                    );
                    break;
                case isset($mapping['reference']) && $mapping['type'] == 'many':
                    $referenceSerializer = self::getReferenceSerializer($field, $classMetadata);
                    foreach($document->$getMethod()->getMongoData() as $referenceDocument){
                        $return[$field][] = $referenceSerializer::serialize(
                            $referenceDocument['$id'],
                            $mapping,
                            $documentManager
                        );
                    }
                    break;
                default:
                    $return[$field] = $document->$getMethod();
            }
        }
        return $return;
    }

    protected static function getReferenceSerializer($field, $classMetadata){
        if (isset($classMetadata->serializer['fields'][$field]['referenceSerializer'])){
            return $classMetadata->serializer['fields'][$field]['referenceSerializer'];
        } else {
            return 'Sds\DoctrineExtensions\Serializer\Reference\Lazy';
        }
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

        // Attempt to load prexisting document from db
        if (isset($data[$metadata->identifier])){
            $document = $documentManager->getProxyFactory()->getProxy($className, $data[$metadata->identifier]);
        }
        if (isset($document)){
            $loadedFromDocumentManager = true;
        } else {
            $loadedFromDocumentManager = false;
            $reflection = new \ReflectionClass($className);
            $document = $reflection->newInstanceWithoutConstructor();
        }

        foreach ($metadata->fieldMappings as $field=>$mapping){

            if (!isset($data[$field])) {
                continue;
            }
            if ($field == $metadata->identifier && $loadedFromDocumentManager){
                continue;
            }

            $setMethod = Accessor::getSetter($metadata, $field, $document);

            switch (true){
                case isset($mapping['embedded']) && $mapping['type'] == 'one':
                    $document->$setMethod(self::unserialize(
                        $data[$field],
                        $documentManager,
                        null,
                        $mapping['targetDocument']
                    ));
                    break;
                case isset($mapping['embedded']) && $mapping['type'] == 'many':
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
                case isset($mapping['reference']) && $mapping['type'] == 'one':
                    if (isset($data[$field]['$ref'])){
                        $pieces = explode('/', $data[$field]['$ref']);
                        $id = $pieces[count($pieces) - 1];
                        $document->$setMethod($documentManager->getReference($mapping['targetDocument'], $id));
                    } else {
                        $document->$setMethod(self::unserialize(
                            $data[$field],
                            $documentManager,
                            null,
                            $mapping['targetDocument']
                        ));
                    }
                    break;
                case isset($mapping['reference']) && $mapping['type'] == 'many':
                    $newArray = [];
                    foreach($data[$field] as $value){
                        if (isset($value['$ref'])){
                            $pieces = explode('/', $value['$ref']);
                            $id = $pieces[count($pieces) - 1];
                            $newArray[] = $documentManager->getReference($mapping['targetDocument'], $id);
                        } else {
                            $newArray[] = self::unserialize(
                                $value,
                                $documentManager,
                                null,
                                $mapping['targetDocument']
                            );
                        }
                    }
                    $document->$setMethod($newArray);
                    break;
                default:
                    $document->$setMethod($data[$field]);
            }
        }

        return $document;
    }
}
