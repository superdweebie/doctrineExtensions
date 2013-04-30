<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Crypt;

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

        $metadata->reflFields[$field]->setValue(
            $document,
            self::hashValue(
                $metadata->reflFields[$field]->getValue($document),
                $metadata->crypt['hash'][$field]
            )
        );
    }

    public static function hashDocument($document, $metadata){

        if ( ! isset($metadata->crypt['hash'])){
            return;
        }

        foreach ($metadata->crypt['hash'] as $field => $config){
            self::hashField($field, $document, $metadata);
        }
    }
}
