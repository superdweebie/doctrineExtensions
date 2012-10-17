<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\Common\State\StateAwareInterface;
use Sds\Common\State\Transition;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Exception;
use Sds\DoctrineExtensions\State\Events as StateEvents;
use Sds\DoctrineExtensions\State\EventArgs as TransitionEventArgs;

/**
 * Emits soft delete events
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
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            Sds\State::event,
            ODMEvents::onFlush
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationState(AnnotationEventArgs $eventArgs)
    {
        $eventArgs->getMetadata()->state = $eventArgs->getReflection()->getName();
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs  $eventArgs)
    {
        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();

        foreach ($unitOfWork->getScheduledDocumentUpdates() AS $document) {

            if (!$document instanceof StateAwareInterface) {
                continue;
            }

            $metadata = $documentManager->getClassMetadata(get_class($document));
            if (!isset($metadata->state)) {
                throw new Exception\DocumentException(sprintf(
                    'Document class %s implements the StateAwareInterface, but does not have a field annotatated as @state.',
                    get_class($document)
                ));
            }

            $eventManager = $documentManager->getEventManager();
            $changeSet = $unitOfWork->getDocumentChangeSet($document);
            $field = $metadata->state;

            if (!isset($changeSet[$field])) {
                continue;
            }

            $fromState = $changeSet[$field][0];
            $toState = $changeSet[$field][1];

            // Raise preTransition
            if ($eventManager->hasListeners(StateEvents::preTransition)) {
                $eventManager->dispatchEvent(
                    StateEvents::preTransition,
                    new TransitionEventArgs(new Transition($fromState, $toState), $document, $documentManager)
                );
            }

            if ($document->getState() == $fromState){
                //State change has been rolled back
                $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
                continue;
            }

            // Raise onTransition
            if ($eventManager->hasListeners(StateEvents::onTransition)) {
                $eventManager->dispatchEvent(
                    StateEvents::onTransition,
                    new TransitionEventArgs(new Transition($fromState, $toState), $document, $documentManager)
                );
            }

            // Force change set update
            $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);

            // Raise postTransition - this is when workflow vars should be updated
            if ($eventManager->hasListeners(StateEvents::postTransition)) {
                $eventManager->dispatchEvent(
                    StateEvents::postTransition,
                    new TransitionEventArgs(new Transition($fromState, $toState), $document, $documentManager)
                );
            }
        }
    }
}