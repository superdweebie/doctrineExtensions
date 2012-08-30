<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DojoModel;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\ClassNamePropertyTrait;

/**
 * Adds dojoModel values to classmetadata
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
            Sds\ClassDojo::event,
            Sds\PropertyDojo::event
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
    public function annotationClassDojo(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $metadataKey = $annotation::metadataKey;

        $dojoMetadata = [];
        if (isset($annotation->className)){
            $dojoMetadata['className'] = true;
            $dojoMetadata['classNameProperty'] = $this->getClassNameProperty();
        }
        if (isset($annotation->discriminator)){
            $dojoMetadata['discriminator'] = true;
        }

        if (isset($annotation->inheritFrom)){
            $dojoMetadata['inheritFrom'] = $annotation->inheritFrom;
        }

        if (isset($annotation->validators)){
            $dojoMetadata['validators'] = [];
            foreach ($annotation->validators as $validator){
                $dojoMetadata['validators'][] = ['module' => $validator->module, 'options' => $validator->options];
            }
        }
        $eventArgs->getMetadata()->$metadataKey = $dojoMetadata;
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationPropertyDojo(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $metadata = $eventArgs->getMetadata();

        if ( ! isset($metadata->{$annotation::metadataKey})){
            $metadata->{$annotation::metadataKey} = [];
        }

        $dojoMetadata = [];
        if (isset($annotation->inputType)){
            $dojoMetadata['inputType'] = $annotation->inputType;
        }
        if (isset($annotation->required)){
            $dojoMetadata['required'] = $annotation->required;
        }
        if (isset($annotation->title)){
            $dojoMetadata['title'] = $annotation->title;
        }
        if (isset($annotation->tooltip)){
            $dojoMetadata['tooltip'] = $annotation->tooltip;
        }
        if (isset($annotation->description)){
            $dojoMetadata['description'] = $annotation->description;
        }

        if (isset($annotation->validators)){
            $dojoMetadata['validators'] = [];
            foreach ($annotation->validators as $validator){
                $dojoMetadata['validators'][] = ['module' => $validator->module, 'options' => $validator->options];
            }
        }

        $metadata->{$annotation::metadataKey}[$eventArgs->getReflection()->getName()] = $dojoMetadata;
    }
}