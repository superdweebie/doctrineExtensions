<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\ClassNamePropertyTrait;

/**
 * Adds serializer values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Subscriber implements EventSubscriber, AnnotationReaderAwareInterface
{
    use AnnotationReaderAwareTrait;
    use ClassNamePropertyTrait;

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            Sds\Serializer::event
        );
    }

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     * @param string $className
     */
    public function __construct(Reader $annotationReader, $classNameProperty){
        $this->setAnnotationReader($annotationReader);
        $this->setClassNameProperty($classNameProperty);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSerializer(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();

        $serializerMetadata = [];

        if (is_array($annotation->value)){
            foreach ($annotation->value as $subAnnotation){
                $serializerMetadata = $this->processAnnotation($subAnnotation, $serializerMetadata);
            }
        } else {
            $serializerMetadata = $this->processAnnotation($annotation->value, $serializerMetadata);
        }

        switch ($eventArgs->getEventType()){
            case 'document':
                $serializerMetadata['classNameProperty'] = $this->getClassNameProperty();
                $eventArgs->getMetadata()->serializer = $serializerMetadata;
                break;
            case 'property':
                $eventArgs->getMetadata()->serializer['fields'][$eventArgs->getReflection()->getName()] = $serializerMetadata;
                break;
        }
    }

    protected function processAnnotation($annotation, $serializerMetadata){

        switch (true){
            case ($annotation instanceof Sds\ClassName):
                $serializerMetadata['className'] = (boolean) $annotation->value;
                break;
            case ($annotation instanceOf Sds\Discriminator):
                $serializerMetadata['discriminator'] = (boolean) $annotation->value;
                break;
            case ($annotation instanceOf Sds\Ignore):
                $serializerMetadata['ignore'] = (boolean) $annotation->value;
                break;
        }

        return $serializerMetadata;
    }
}