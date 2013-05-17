<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Zone;

use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AnnotationSubscriber implements EventSubscriber
{

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
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
        'extension.annotation' => true
    );
}