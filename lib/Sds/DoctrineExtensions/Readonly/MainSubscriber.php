<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Readonly;

use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\DoctrineExtensions\AbstractLazySubscriber;
use Sds\DoctrineExtensions\Readonly\Events as ReadonlyEvents;
use Sds\DoctrineExtensions\Readonly\EventArgs;

/**
 * Listener enforces readonly annotation
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
            ODMEvents::onFlush
        ];
    }

    /**
     *
     * @param OnFlushEventArgs $eventArgs
     * @throws Sds\DoctrineExtensions\Exception\BadMethodCallException
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();
        $eventManager = $documentManager->getEventManager();

        foreach ($unitOfWork->getScheduledDocumentUpdates() AS $document) {
            $changeSet = $unitOfWork->getDocumentChangeSet($document);
            $metadata = $documentManager->getClassMetadata(get_class($document));
            foreach ($changeSet as $field => $change){
                $old = $change[0];
                $new = $change[1];

                // Check for change and readonly annotation
                if(!isset($metadata->fieldMappings[$field]['readonly']) ||
                    $old == null ||
                    $old == $new
                ){
                    continue;
                }

                // Raise preReadonlyRollback
                if ($eventManager->hasListeners(ReadonlyEvents::preReadonlyRollback)) {
                    $eventManager->dispatchEvent(
                        ReadonlyEvents::preReadonlyRollback,
                        new EventArgs($field, $old, $new, $document, $documentManager)
                    );
                    $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
                    $changeSet = $unitOfWork->getDocumentChangeSet($document);
                    $new = $changeSet[$field][1];

                    // Continue if value has been changed back to old.
                    if($old == $new) {
                        continue;
                    }
                }

                $metadata->reflFields[$field]->setValue($document, $old);
                $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);

                // Raise postReadonlyRollback
                if ($eventManager->hasListeners(ReadonlyEvents::postReadonlyRollback)) {
                    $eventManager->dispatchEvent(
                        ReadonlyEvents::postReadonlyRollback,
                        new EventArgs($field, $old, $new, $document, $documentManager)
                    );
                }
            }
        }
    }
}
