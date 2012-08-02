<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Cryptographically hash the property value before persisting
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class CryptHash extends Annotation
{
    const event = 'annotationCryptHash';

    const metadataKey = 'cryptHash';
    
    /**
     * FQCN of a class that implements Sds\Common\Crypt\SaltInterface.
     * Gets the salt used to create the hash
     * 
     * @var string
     */
    public $value = 'Sds\Common\Crypt\Hash';
}