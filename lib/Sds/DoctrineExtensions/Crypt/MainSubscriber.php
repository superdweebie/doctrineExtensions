<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Crypt;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\DoctrineExtensions\AbstractLazySubscriber;


/**
 * Listener hashes fields marked with CryptHash annotation
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class MainSubscriber extends AbstractLazySubscriber
{

    /**
     * @return array
     */
    public static function getStaticSubscribedEvents(){
        return [
            ODMEvents::prePersist,
            ODMEvents::onFlush
        ];
    }

    /**
     *
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();

        foreach ($unitOfWork->getScheduledDocumentUpdates() as $document) {
            $changeSet = $unitOfWork->getDocumentChangeSet($document);
            $metadata = $documentManager->getClassMetadata(get_class($document));
            foreach ($changeSet as $field => $change){
                $old = $change[0];
                $new = $change[1];

                // Check for change
                if ($old == null || $old == $new){
                    continue;
                }

                if ( ! isset($new) || $new == ''){
                    continue;
                }

                $requireRecompute = false;

                if(isset($metadata->crypt['hash']) &&
                   isset($metadata->crypt['hash'][$field])
                ){
                    HashService::hashField($field, $document, $metadata);
                    $requireRecompute = true;

                } elseif (isset($metadata->crypt['blockCipher']) &&
                   isset($metadata->crypt['blockCipher'][$field])
                ){
                    BlockCipherService::encryptField($field, $document, $metadata);
                    $requireRecompute = true;
                }

                if ($requireRecompute){
                    $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
                }
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

        if (isset($metadata->crypt['hash'])) {
            foreach ($metadata->crypt['hash'] as $field => $config){
                HashService::hashField($field, $document, $metadata);
            }
        }

        if (isset($metadata->crypt['blockCipher'])) {
            foreach ($metadata->crypt['blockCipher'] as $field => $config){
                BlockCipherService::encryptField($field, $document, $metadata);
            }
        }
    }
}
