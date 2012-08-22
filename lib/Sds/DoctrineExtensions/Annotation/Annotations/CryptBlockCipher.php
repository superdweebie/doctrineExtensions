<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Encrypt the property value before persisting, and decrypt on retrieval
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class CryptBlockCipher extends Annotation
{
    const event = 'annotationCryptBlockCipher';

    const metadataKey = 'cryptBlockCipher';

    /**
     * FQCN of a class that implements Sds\Common\Crypt\BlockCipherInterface.
     * Responsible for encrypt and decrypt
     *
     * Default uses Zend\Crypt\BlockCipher
     *
     * @var string
     */
    public $blockCipherClass = 'Sds\DoctrineExtensions\Crypt\ZendBlockCipher';

    /**
     * FQCN of a class that implements Sds\Common\Crypt\KeyInterface.
     * Supplies the key used for crypt
     *
     * @var string
     */
    public $keyClass;
}