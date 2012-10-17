<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State\AccessControl;

use Sds\DoctrineExtensions\AccessControl\AbstractAccessControlSubscriber;
use Sds\DoctrineExtensions\AccessControl\AccessController;
use Sds\DoctrineExtensions\State\AccessControl\Events as AccessControlEvents;
use Sds\DoctrineExtensions\State\EventArgs as TransitionEventArgs;
use Sds\DoctrineExtensions\State\Events as StateEvents;
use Sds\DoctrineExtensions\State\Transition;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class TransitionSubscriber extends AbstractAccessControlSubscriber
{
    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            StateEvents::preTransition
        );
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function preTransition(TransitionEventArgs $eventArgs)
    {
        $fromState = $eventArgs->getTransition()->getFromState();
        $document = $eventArgs->getDocument();
        $documentManager = $eventArgs->getDocumentManager();
        $action = $eventArgs->getTransition()->getAction();

        if ( AccessController::isAccessControlEnabled($documentManager->getClassMetadata(get_class($document)), $action, true) &&
            !AccessController::isActionAllowed($document, $action, $this->roles, $fromState)
        ) {
            //stop state change
            $document->setState($fromState);

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(AccessControlEvents::transitionDenied)) {
                $eventManager->dispatchEvent(
                    AccessControlEvents::transitionDenied,
                    $eventArgs
                );
            }
        }
    }
}
