<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Workflow;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\PreFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\Common\Workflow\WorkflowAwareInterface;
use Sds\DoctrineExtensions\State\Events as StateEvents;
use Sds\DoctrineExtensions\State\EventArgs as StateEventArgs;
use Sds\DoctrineExtensions\Workflow\Events as WorkflowEvents;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Subscriber implements EventSubscriber
{
    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            ODMEvents::preFlush,
            StateEvents::preStateChange,
            StateEvents::onStateChange
        );
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\PreFlushEventArgs $eventArgs
     */
    public function preFlush(PreFlushEventArgs $eventArgs) {
        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();

        //Set startState for documents with workflow
        foreach ($unitOfWork->getScheduledDocumentInsertions() as $document) {
            if (!$document instanceof WorkflowAwareInterface){
                continue;
            }
            $document->setState($document->getWorkflow()->getStartState());
        }
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\State\Event\EventArgs $eventArgs
     */
    public function preStateChange(StateEventArgs $eventArgs) {
        $document = $eventArgs->getDocument();

        if (!$document instanceof WorkflowAwareInterface){
            return;
        }

        $fromState = $eventArgs->getFromState();
        $toState = $eventArgs->getToState();

        foreach ($document->getWorkflow()->getTransitions() as $transition){
            if ($transition->getFromState() == $fromState &&
                $transition->getToState() == $toState
            ) {
                // Transition extists. State change ok.
                return;
            }
        }

        // Transition does not exist. Roll back state change
        $document->setState($fromState);

        // Raise transitionDoesNotExist
        $eventManager = $eventArgs->getDocumentManager()->getEventManager();
        if ($eventManager->hasListeners(WorkflowEvents::transitionDoesNotExist)) {
            $eventManager->dispatchEvent(
                WorkflowEvents::transitionDoesNotExist,
                $eventArgs
            );
        }
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\State\Event\EventArgs $eventArgs
     */
    public function onStateChange(StateEventArgs $eventArgs) {

        $document = $eventArgs->getDocument();

        if (!$document instanceof WorkflowAwareInterface){
            return;
        }

        // Raise updateWorkflowVars
        $eventManager = $eventArgs->getDocumentManager()->getEventManager();
        if ($eventManager->hasListeners(WorkflowEvents::updateWorkflowVars)) {
            $eventManager->dispatchEvent(
                WorkflowEvents::updateWorkflowVars,
                $eventArgs
            );
        }
    }
}