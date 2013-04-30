<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer;

use Sds\DoctrineExtensions\AbstractLazySubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;

/**
 * Adds serializer values to classmetadata
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
            Sds\Serializer\ClassName::event,
            Sds\Serializer\Discriminator::event,
            Sds\Serializer\Eager::event,
            Sds\Serializer\Ignore::event,
            Sds\Serializer\RefLazy::event,
            Sds\Serializer\ReferenceSerializer::event,
            Sds\Serializer\SimpleLazy::event,
        ];
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSerializerClassName(AnnotationEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getMetadata();
        $annotation = $eventArgs->getAnnotation();
        $this->createMetadata($metadata);
        $metadata->serializer['className'] = (boolean) $annotation->value;
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSerializerDiscriminator(AnnotationEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getMetadata();
        $annotation = $eventArgs->getAnnotation();
        $this->createMetadata($metadata);
        $metadata->serializer['discriminator'] = (boolean) $annotation->value;
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSerializerEager(AnnotationEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getMetadata();
        $this->createMetadata($metadata);
        $metadata->serializer['fields'][$eventArgs->getReflection()->getName()]['referenceSerializer'] =
            'eagerReferenceSerializer';
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSerializerIgnore(AnnotationEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getMetadata();
        $annotation = $eventArgs->getAnnotation();
        $this->createMetadata($metadata);
        $metadata->serializer['fields'][$eventArgs->getReflection()->getName()]['ignore'] =
            $annotation->value;
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSerializerRefLazy(AnnotationEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getMetadata();
        $this->createMetadata($metadata);
        $metadata->serializer['fields'][$eventArgs->getReflection()->getName()]['referenceSerializer'] =
            'refLazyReferenceSerializer';
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSerializerReferenceSerializer(AnnotationEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getMetadata();
        $annotation = $eventArgs->getAnnotation();
        $this->createMetadata($metadata);
        $metadata->serializer['fields'][$eventArgs->getReflection()->getName()]['referenceSerializer'] =
            $annotation->value;
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationSerializerSimpleLazy(AnnotationEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getMetadata();
        $this->createMetadata($metadata);
        $metadata->serializer['fields'][$eventArgs->getReflection()->getName()]['referenceSerializer'] =
            'simpleLazyReferenceSerializer';
    }

    protected function createMetadata($metadata){
        if ( ! isset($metadata->serializer)){
            $metadata->serializer = [
                'fields'   => []
            ];
        }
    }
}