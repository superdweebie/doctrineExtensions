<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Dojo;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Annotation\Events as AnnotationEvents;
use Sds\DoctrineExtensions\Annotation\EventType;
use Sds\DoctrineExtensions\Dojo\Generator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Adds dojo values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AnnotationSubscriber implements EventSubscriber, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    protected $serializer;

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            Sds\Dojo\Model::event,
            Sds\Dojo\Input::event,
            Sds\Dojo\Form::event,
            Sds\Dojo\Validator::event,
            Sds\Dojo\MultiFieldValidator::event,
            Sds\Dojo\ModelValidator::event,
            Sds\Dojo\JsonRest::event,
            AnnotationEvents::postBuildMetadata
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDojoModel(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        if ($annotation->generate){
            $metadata = $eventArgs->getMetadata();
            $metadata->generator[Generator\Model::getResourceName($metadata->name)] = [
                'event' => Generator\Model::event,
                'options' => [
                    'mixins' => $annotation->mixins,
                    'params' => $annotation->params
                ]
            ];
        }
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDojoForm(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        if ($annotation->generate){
            $metadata = $eventArgs->getMetadata();
            $metadata->generator[Generator\Form::getResourceName($metadata->name)] = [
                'event' => Generator\Form::event,
                'options' => [
                    'mixins' => $annotation->mixins,
                    'params' => $annotation->params
                ]
            ];
        }
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDojoInput(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        if ($annotation->generate){
            $metadata = $eventArgs->getMetadata();
            $field = $eventArgs->getReflection()->name;
            $metadata->generator[Generator\Input::getResourceName($metadata->name, $field)] = [
                'event' => Generator\Input::event,
                'options' => [
                    'field' => $field,
                    'mixins' => $annotation->mixins,
                    'params' => $annotation->params
                ]
            ];
        }
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDojoValidator(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        if ($annotation->generate){
            $metadata = $eventArgs->getMetadata();
            $field = $eventArgs->getReflection()->name;
            $metadata->generator[Generator\Validator::getResourceName($metadata->name, $field)] = [
                'event' => Generator\Validator::event,
                'options' => [
                    'field' => $field,
                    'mixins' => $annotation->mixins,
                    'params' => $annotation->params
                ]
            ];
        }
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDojoMultiFieldValidator(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        if ($annotation->generate){
            $metadata = $eventArgs->getMetadata();
            $metadata->generator[Generator\MultiFieldValidator::getResourceName($metadata->name)] = [
                'event' => Generator\MultiFieldValidator::event,
                'options' => [
                    'mixins' => $annotation->mixins,
                    'params' => $annotation->params
                ]
            ];
        }
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDojoModelValidator(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        if ($annotation->generate){
            $metadata = $eventArgs->getMetadata();
            $metadata->generator[Generator\ModelValidator::getResourceName($metadata->name)] = [
                'event' => Generator\ModelValidator::event,
                'options' => [
                    'mixins' => $annotation->mixins,
                    'params' => $annotation->params
                ]
            ];
        }
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDojoJsonRest(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        if ($annotation->generate){
            $metadata = $eventArgs->getMetadata();
            $metadata->generator[Generator\JsonRest::getResourceName($metadata->name)] = [
                'event' => Generator\JsonRest::event,
                'options' => [
                    'mixins' => $annotation->mixins,
                    'params' => $annotation->params
                ]
            ];
        }
    }

    public function postBuildMetadata(LoadClassMetadataEventArgs $eventArgs){
        $metadata = $eventArgs->getClassMetadata();

        if ( ! isset($metadata->generator)){
            return;
        }

        //Add implied generatable inputs
        if (array_key_exists(Generator\Form::getResourceName($metadata->name), $metadata->generator)){
            foreach($this->getSerializer()->fieldListForUnserialize($metadata) as $field){
                if ( ! array_key_exists(Generator\Input::getResourceName($metadata->name, $field), $metadata->generator)){
                    $this->annotationDojoInput(new AnnotationEventArgs(
                        $metadata,
                        EventType::field,
                        new Sds\Dojo\Input([]),
                        $metadata->reflFields[$field],
                        $eventArgs->getDocumentManager()->getEventManager()
                    ));
                }
            }
        }

        //Add implied generatable validators
        foreach($this->getSerializer()->fieldListForUnserialize($metadata) as $field){
            if (
                array_key_exists(Generator\Input::getResourceName($metadata->name, $field), $metadata->generator) &&
                array_key_exists($field, $metadata->validator['fields']) &&
                ! array_key_exists(Generator\Validator::getResourceName($metadata->name, $field), $metadata->generator)
            ){
                $this->annotationDojoValidator(new AnnotationEventArgs(
                    $metadata,
                    EventType::field,
                    new Sds\Dojo\Validator([]),
                    $metadata->reflFields[$field],
                    $eventArgs->getDocumentManager()->getEventManager()
                ));
            }
        }

        //Add implied multifieldvalidator
        if ((array_key_exists(Generator\Form::getResourceName($metadata->name), $metadata->generator) ||
             array_key_exists(Generator\ModelValidator::getResourceName($metadata->name), $metadata->generator)) &&
            array_key_exists('document', $metadata->validator) &&
            ! array_key_exists(Generator\MultiFieldValidator::getResourceName($metadata->name), $metadata->generator)
        ) {
            $this->annotationDojoMultiFieldValidator(new AnnotationEventArgs(
                $metadata,
                EventType::document,
                new Sds\Dojo\MultiFieldValidator([]),
                $metadata->reflClass,
                $eventArgs->getDocumentManager()->getEventManager()
            ));
        }
    }

    protected function getSerializer(){
        if (!isset($this->serializer)){
            $this->serializer = $this->serviceLocator->get('serializer');
        }
        return $this->serializer;
    }
}