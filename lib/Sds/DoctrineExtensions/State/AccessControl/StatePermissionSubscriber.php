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

        $config = [
            'factory' => 'Sds\DoctrineExtensions\State\AccessControl\StatePermissionFactory',
            'options' => [
                'state' => $annotation->state
            ]
        ];

        if (isset($annotation->roles)){
            if (is_array($annotation->roles)){
                $config['options']['roles'] = $annotation->roles;
            } else {
                $config['options']['roles'] = [$annotation->roles];
            }
        } else {
            $config['options']['roles'] = [];
        }

        if (isset($annotation->allow)){
            if (is_array($annotation->allow)){
                $config['options']['allow'] = $annotation->allow;
            } else {
                $config['options']['allow'] = [$annotation->allow];
            }
        } else {
            $config['options']['allow'] = [];
        }

        if (isset($annotation->deny)){
            if (is_array($annotation->deny)){
                $config['options']['deny'] = $annotation->deny;
            } else {
                $config['options']['deny'] = [$annotation->deny];
            }
        } else {
            $config['options']['deny'] = [];
        }

        $metadata->permissions[] = $config;
    }
}