<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Zone;

use Sds\DoctrineExtensions\AbstractLazySubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AnnotationSubscriber extends AbstractLazySubscriber
{

    /**
     *
     * @return array
     */
    public static function getStaticSubscribedEvents(){
        return [
            Sds\Zones::event
        ];
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\EventArgs $eventArgs
     */
    public function annotationZones(AnnotationEventArgs $eventArgs)
    {
        $eventArgs->getMetadata()->zones = $eventArgs->getReflection()->getName();
    }

    /**
     *
     * @var array
     */
    protected $dependencies = array(
        'Sds\DoctrineExtensions\Annotation' => true
    );
}