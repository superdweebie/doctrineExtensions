<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer;

use Doctrine\ODM\MongoDB\DocumentManager;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * Provides static methods for serializing documents
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Serializer {

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
     *
     * @param object $document
     * @param DocumentManager $documentManager
     * @return array
     * @throws \BadMethodCallException
     */
    protected static function serialize($document, DocumentManager $documentManager){

        $classMetadata = $documentManager->getClassMetadata(get_class($document));
        $return = array();
        foreach ($classMetadata->fieldMappings as $field=>$mapping){
            if(isset($mapping[Sds\DoNotSerialize::metadataKey]) &&
                $mapping[Sds\DoNotSerialize::metadataKey]
            ){
                continue;
            }

            if(isset($mapping[Sds\Getter::metadataKey])
            ){
                $getMethod = $mapping[Sds\Getter::metadataKey];
            } else {
                $getMethod = 'get'.ucfirst($field);
            }

            if (!method_exists($document, $getMethod)){
                throw new \BadMethodCallException(sprintf(
                    'Method %s not found. This method was defined in the @getter annotation
                        to be used for getting a field',
                    $getMethod
                ));
            }

            if(isset($mapping['embedded'])){
                switch ($mapping['type']){
                    case 'one':
                        $return[$field] = self::serialize($document->$getMethod(), $documentManager);
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
}
