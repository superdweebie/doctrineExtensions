<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Dojo;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\ClassNamePropertyTrait;

/**
 * Adds dojo values to classmetadata
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
            Sds\DojoModel::event,
            Sds\DojoInput::event,
            Sds\DojoForm::event,
            Sds\DojoValidator::event
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
    public function annotationDojoModel(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();

        $eventArgs->getMetadata()->dojo['model'] = [
            'base' => $annotation->base,
            'params' => $annotation->params,
            'gets' => $annotation->gets,
            'proxies' => $annotation->proxies
        ];
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDojoForm(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();

        if (!isset($eventArgs->getMetadata()->dojo['form'])){
            $eventArgs->getMetadata()->dojo['form'] = [];
        }

        if (isset($annotation->value) && $annotation->value instanceof Sds\Ignore){
            $eventArgs->getMetadata()->dojo['form']['ignore'] = $annotation->value->value;
        } else {
            $eventArgs->getMetadata()->dojo['form'] = array_merge($eventArgs->getMetadata()->dojo['form'], [
                'base' => $annotation->base,
                'params' => $annotation->params,
                'gets' => $annotation->gets,
                'proxies' => $annotation->proxies
            ]);
        }
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDojoInput(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();

        $eventArgs->getMetadata()->dojo['fields'][$eventArgs->getReflection()->getName()]['input'] = [
            'base' => $annotation->base,
            'params' => $annotation->params,
            'gets' => $annotation->gets,
            'proxies' => $annotation->proxies
        ];
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDojoValidator(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();

        switch ($eventArgs->getEventType()){
            case 'document':
                $eventArgs->getMetadata()->dojo['validator'] = [
                    'base' => $annotation->base,
                    'params' => $annotation->params,
                    'gets' => $annotation->gets,
                    'proxies' => $annotation->proxies
                ];
                break;
            case 'property':
                $eventArgs->getMetadata()->dojo['fields'][$eventArgs->getReflection()->getName()]['validator'] = [
                    'base' => $annotation->base,
                    'params' => $annotation->params,
                    'gets' => $annotation->gets,
                    'proxies' => $annotation->proxies
                ];
                break;
        }
    }
}