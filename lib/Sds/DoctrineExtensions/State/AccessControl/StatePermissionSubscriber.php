<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State\AccessControl;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class StatePermissionSubscriber implements EventSubscriber {

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return [Sds\Permission\State::event];
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationStatePermission(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $metadata = $eventArgs->getMetadata();

        if ( !isset($metadata->permissions)){
            $metadata->permissions = [];
        }
        $metadata->permissions[] = [
            'factory' => 'Sds\DoctrineExtensions\State\AccessControl\StatePermissionFactory',
            'options' => [
                'roles' => is_array($annotation->roles) ? $annotation->roles : [$annotation->roles],
                'allow' => is_array($annotation->allow) ? $annotation->allow : [$annotation->allow],
                'deny' => is_array($annotation->deny) ? $annotation->deny : [$annotation->deny],
                'state' => $annotation->state
            ]
        ];
    }
}