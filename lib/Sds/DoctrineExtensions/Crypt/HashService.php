<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Crypt;

use Sds\DoctrineExtensions\Accessor\Accessor;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class HashService {

    public static function hashValue($value, $config){
        if ($config['prependSalt']){
            return $config['hashClass']::hashAndPrependSalt($config['saltClass']::getSalt(), $value);
        } else {
            return $config['hashClass']::hash($config['saltClass']::getSalt(), $value);
        }
    }

    public static function hashField($field, $document, $metadata){
        $setMethod = Accessor::getSetter($metadata, $field, $document);
        $getMethod = Accessor::getGetter($metadata, $field, $document);

        $document->$setMethod(self::hashValue($document->$getMethod(), $metadata->{Sds\CryptHash::metadataKey}[$field]));
    }

    public static function hashDocument($document, $metadata){

        if ( ! isset($metadata->{Sds\CryptHash::metadataKey})){
            return;
        }

        foreach ($metadata->{Sds\CryptHash::metadataKey} as $field => $config){
            self::hashField($field, $document, $metadata);
        }
    }
}
