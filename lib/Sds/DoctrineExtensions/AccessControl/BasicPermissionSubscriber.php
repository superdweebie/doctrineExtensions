<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

use Sds\DoctrineExtensions\AbstractLazySubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class BasicPermissionSubscriber extends AbstractLazySubscriber {

    /**
     *
     * @return array
     */
    public static function getStaticSubscribedEvents(){
        return array(
            Sds\Permission\Basic::event
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationBasicPermission(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $metadata = $eventArgs->getMetadata();

        if ( !isset($metadata->permissions)){
            $metadata->permissions = [];
        }
        $metadata->permissions[] = [
            'factory' => 'Sds\DoctrineExtensions\AccessControl\BasicPermissionFactory',
            'options' => [
                'roles' => is_array($annotation->roles) ? $annotation->roles : [$annotation->roles],
                'allow' => is_array($annotation->allow) ? $annotation->allow : [$annotation->allow],
                'deny' => is_array($annotation->deny) ? $annotation->deny : [$annotation->deny]
            ]
        ];
    }
}