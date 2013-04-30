<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Crypt;

use Sds\DoctrineExtensions\AbstractLazySubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Exception;

/**
 * Listener hashes fields marked with CryptHash annotation
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AnnotationSubscriber extends AbstractLazySubscriber
{

    /**
     * @return array
     */
    public static function getStaticSubscribedEvents(){
        return [
            Sds\Crypt\Hash::event,
            Sds\Crypt\BlockCipher::event
        ];
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationCryptHash(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();

        if ( ! in_array('Sds\Common\Crypt\SaltInterface', class_implements($annotation->saltClass))) {
            throw new Exception\DocumentException(sprintf('Class %s given in @CryptHash must implement SaltInterface', $annotation->saltClass));
        }
        $eventArgs->getMetadata()->crypt['hash'][$eventArgs->getReflection()->getName()] = array(
            'hashClass' => $annotation->hashClass,
            'saltClass' => $annotation->saltClass,
            'prependSalt' => $annotation->prependSalt
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationCryptBlockCipher(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();

        if ( ! in_array('Sds\Common\Crypt\BlockCipherInterface', class_implements($annotation->blockCipherClass))) {
            throw new Exception\DocumentException(sprintf('Class %s given in @CryptBlockCipher must implement BlockCipherInterface', $annotation->saltClass));
        }

        if ( ! in_array('Sds\Common\Crypt\KeyInterface', class_implements($annotation->keyClass))) {
            throw new Exception\DocumentException(sprintf('Class %s given in @CryptBlockCipher must implement KeyInterface', $annotation->keyClass));
        }

        $eventArgs->getMetadata()->crypt['blockCipher'][$eventArgs->getReflection()->getName()] = array(
            'blockCipherClass' => $annotation->blockCipherClass,
            'keyClass' => $annotation->keyClass,
            'saltClass' => $annotation->saltClass
        );
    }
}
