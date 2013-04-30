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
class BlockCipherService {

    public static function encryptValue($value, $config){
        if ( ! isset($value) || $value == ''){
            return $value;
        }
        $salt = isset($config['saltClass']) ? $config['saltClass']::getSalt() : null;
        return $config['blockCipherClass']::encrypt($value, $config['keyClass']::getKey(), $salt);
    }

    public static function decryptValue($value, $config){
        return $config['blockCipherClass']::decrypt($value, $config['keyClass']::getKey());
    }

    public static function encryptField($field, $document, $metadata){

        $metadata->reflFields[$field]->setValue(
            $document,
            self::encryptValue(
                $metadata->reflFields[$field]->getValue($document),
                $metadata->crypt['blockCipher'][$field]
            )
        );
    }

    public static function decryptField($field, $document, $metadata){

        $metadata->reflFields[$field]->setValue(
            $document,
            self::decryptValue(
                $metadata->reflFields[$field]->getValue($document),
                $metadata->crypt['blockCipher'][$field]
            )
        );
    }

    public static function encryptDocument($document, $metadata){

        if ( ! isset($metadata->crypt['blockCipher'])){
            return;
        }

        foreach ($metadata->crypt['blockCipher'] as $field => $config){
            self::encryptField($field, $document, $metadata);
        }
    }

    public static function decryptDocument($document, $metadata){

        if ( ! isset($metadata->crypt['blockCipher'])){
            return;
        }

        foreach ($metadata->crypt['blockCipher'] as $field => $config){
            self::decryptField($field, $document, $metadata);
        }
    }
}
