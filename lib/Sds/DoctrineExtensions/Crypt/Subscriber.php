<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Crypt;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\Common\Crypt\Hash;
use Sds\DoctrineExtensions\Accessor\Accessor;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Exception;

/**
 * Listener hashes fields marked with CryptHash annotation
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Subscriber implements EventSubscriber, AnnotationReaderAwareInterface
{
    use AnnotationReaderAwareTrait;

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     */
    public function __construct(Reader $annotationReader){
        $this->setAnnotationReader($annotationReader);
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            Sds\CryptHash::event,
            ODMEvents::prePersist,
            ODMEvents::onFlush
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationCryptHash(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();

        if ( ! in_array('Sds\Common\Crypt\SaltInterface', class_implements($annotation->saltClass))) {
        //if (! $annotation->value instanceof SaltInterface) {
            throw new Exception\DocumentException(sprintf('Class %s given in @CryptHash must implement SaltInterface', $annotation->saltClass));
        }
        $eventArgs->getMetadata()->fieldMappings[$eventArgs->getReflection()->getName()][$annotation::metadataKey] = array(
            'saltClass' => $annotation->saltClass,
            'prependSalt' => $annotation->prependSalt
        );
    }

    /**
     *
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();

        foreach ($unitOfWork->getScheduledDocumentUpdates() AS $document) {
            $changeSet = $unitOfWork->getDocumentChangeSet($document);
            $metadata = $documentManager->getClassMetadata(get_class($document));
            foreach ($changeSet as $field => $change){
                $old = $change[0];
                $new = $change[1];

                // Check for change and crypthash annotation
                $config = $metadata->fieldMappings[$field][Sds\CryptHash::metadataKey];
                if(!isset($config) ||
                    $old == null ||
                    $old == $new
                ){
                    continue;
                }

                $setMethod = Accessor::getSetter($metadata, $field, $document);
                if ($config['prependSalt']) {
                    $setValue = Hash::hashAndPrependSalt($config['saltClass']::getSalt(), $new);
                } else {
                    $setValue = Hash::hash($config['saltClass']::getSalt(), $new);
                }
                $document->$setMethod($setValue);
                $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
            }
        }
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs) {
        $document = $eventArgs->getDocument();
        $documentManager = $eventArgs->getDocumentManager();
        $metadata = $documentManager->getClassMetadata(get_class($document));

        foreach ($metadata->fieldMappings as $field => $mapping){
            if ( ! isset($metadata->fieldMappings[$field][Sds\CryptHash::metadataKey])) {
                continue;
            }

            $getMethod = Accessor::getGetter($metadata, $field, $document);
            $setMethod = Accessor::getSetter($metadata, $field, $document);
            $config = $metadata->fieldMappings[$field][Sds\CryptHash::metadataKey];
            
            if ($config['prependSalt']) {
                $setValue = Hash::hashAndPrependSalt($config['saltClass']::getSalt(), $document->$getMethod());
            } else {
                $setValue = Hash::hash($config['saltClass']::getSalt(), $document->$getMethod());
            }
            $document->$setMethod($setValue);
        }
    }
}
