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
class BlockCipherService {

    public static function encryptDocument($document, $metadata){

        if ( ! isset($metadata->{Sds\CryptBlockCipher::metadataKey})){
            return;
        }

        foreach ($metadata->{Sds\CryptBlockCipher::metadataKey} as $field => $config){
            $getMethod = Accessor::getGetter($metadata, $field, $document);
            $setMethod = Accessor::getSetter($metadata, $field, $document);

            $document->$setMethod($config['blockCipherClass']::encrypt($document->$getMethod(), $config['keyClass']::getKey()));
        }
    }

    public static function decryptDocument($document, $metadata){

        foreach ($metadata->{Sds\CryptBlockCipher::metadataKey} as $field => $config){
            $getMethod = Accessor::getGetter($metadata, $field, $document);
            $setMethod = Accessor::getSetter($metadata, $field, $document);

            $document->$setMethod($config['blockCipherClass']::decrypt($document->$getMethod(), $config['keyClass']::getKey()));
        }
    }
}
