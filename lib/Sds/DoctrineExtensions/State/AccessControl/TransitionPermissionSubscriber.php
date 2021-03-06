<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State\AccessControl;

use Sds\DoctrineExtensions\AccessControl\AbstractAccessControlSubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\State\EventArgs as TransitionEventArgs;
use Sds\DoctrineExtensions\State\Events as Events;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class TransitionPermissionSubscriber extends AbstractAccessControlSubscriber {

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return [
            Sds\Permission\Transition::event,
            Events::preTransition
        ];
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationTransitionPermission(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $metadata = $eventArgs->getMetadata();

        if ( !isset($metadata->permissions)){
            $metadata->permissions = [];
        }
        $metadata->permissions[] = [
            'factory' => 'Sds\DoctrineExtensions\State\AccessControl\TransitionPermissionFactory',
            'options' => [
                'roles' => is_array($annotation->roles) ? $annotation->roles : [$annotation->roles],
                'allow' => is_array($annotation->allow) ? $annotation->allow : [$annotation->allow],
                'deny' => is_array($annotation->deny) ? $annotation->deny : [$annotation->deny]
            ]
        ];
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function preTransition(TransitionEventArgs $eventArgs)
    {
        if (! ($accessController = $this->getAccessController())){
            //Access control is not enabled
            return;
        }

        $document = $eventArgs->getDocument();
        $documentManager = $eventArgs->getDocumentManager();
        $eventManager = $documentManager->getEventManager();
        $action = $eventArgs->getTransition()->getAction();

        if ( ! $accessController->isAllowed($action, null, $document)->getIsAllowed()) {
            //stop transition
            $document->setState($eventArgs->getTransition()->getFrom());

            $eventManager = $eventArgs->getDocumentManager()->getEventManager();
            if ($eventManager->hasListeners(Events::transitionDenied)) {
                $eventManager->dispatchEvent(
                    Events::transitionDenied,
                    $eventArgs
                );
            }
        }
    }
}
