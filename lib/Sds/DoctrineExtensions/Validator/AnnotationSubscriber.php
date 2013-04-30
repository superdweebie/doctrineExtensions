<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Sds\DoctrineExtensions\AbstractLazySubscriber;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Annotation\EventType;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AnnotationSubscriber extends AbstractLazySubscriber {

    /**
     *
     * @return array
     */
    public static function getStaticSubscribedEvents(){
        $events = [
            Sds\Validator\Alpha::event,
            Sds\Validator\Credential::event,
            Sds\Validator\Currency::event,
            Sds\Validator\EmailAddress::event,
            Sds\Validator\IdentifierArray::event,
            Sds\Validator\Identifier::event,
            Sds\Validator\Inequality::event,
            Sds\Validator\Length::event,
            Sds\Validator\NotRequired::event,
            Sds\Validator\PersonalName::event,
            Sds\Validator\Required::event,
            Sds\Validator::event,
        ];
        return $events;
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationAlphaValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Validator\Alpha']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationCredentialValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Validator\Credential']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationCurrencyValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Validator\Currency']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationEmailAddressValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Validator\EmailAddress']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationIdentifierArrayValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Validator\IdentifierArray']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationIdentifierValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Validator\Identifier']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationInequalityValidator(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $this->addFieldValidator($eventArgs, [
            'class' => 'Sds\Validator\Inequality',
            'options' => [
                'compareValue' => $annotation->compareValue,
                'operator' => $annotation->operator
            ]
        ]);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationLengthValidator(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $this->addFieldValidator($eventArgs, [
            'class' => 'Sds\Validator\Length',
            'options' => [
                'min' => $annotation->min,
                'max' => $annotation->max
            ]
        ]);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationNotRequiredValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Validator\NotRequired']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationPersonalNameValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Validator\PersonalName']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationRequiredValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Validator\Required']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationValidator(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();

        switch ($eventArgs->getEventType()){
            case EventType::document:
                $this->addDocumentValidator($eventArgs, [
                    'class' => $annotation->class,
                    'options' => $annotation->options
                ]);
                break;
                break;
            case EventType::field:
                $this->addFieldValidator($eventArgs, [
                    'class' => $annotation->class,
                    'options' => $annotation->options
                ]);
                break;
        }
    }

    protected function addFieldValidator($eventArgs, $definition){
        if ($eventArgs->getAnnotation()->value){

            if (isset($eventArgs->getMetadata()->validator['fields'][$eventArgs->getReflection()->getName()])){
                foreach ($eventArgs->getMetadata()->validator['fields'][$eventArgs->getReflection()->getName()] as $index => $setDefinition){
                    if ($setDefinition['class'] == $definition['class']){
                        $eventArgs->getMetadata()->validator['fields'][$eventArgs->getReflection()->getName()][$index] = $definition;
                        return;
                    }
                }
            }
            $eventArgs->getMetadata()->validator['fields'][$eventArgs->getReflection()->getName()][] = $definition;
        } else {
            foreach ($eventArgs->getMetadata()->validator['fields'][$eventArgs->getReflection()->getName()] as $index => $setDefinition){
                if ($setDefinition['class'] == $definition['class']){
                    array_splice($eventArgs->getMetadata()->validator['fields'][$eventArgs->getReflection()->getName()], $index, 1);
                }
            }
        }
    }

    protected function addDocumentValidator($eventArgs, $definition){
        if ($eventArgs->getAnnotation()->value){
            if (isset($eventArgs->getMetadata()->validator['document'])){
                foreach ($eventArgs->getMetadata()->validator['document'] as $index => $setDefinition){
                    if ($setDefinition['class'] == $definition['class']){
                        $eventArgs->getMetadata()->validator['document'][$index] = $definition;
                        return;
                    }
                }
            }
            $eventArgs->getMetadata()->validator['document'][] = $definition;
        } else {
            foreach ($eventArgs->getMetadata()->validator['document'] as $index => $setDefinition){
                if ($setDefinition['class'] == $definition['class']){
                    array_splice($eventArgs->getMetadata()->validator['document'], $index, 1);
                }
            }
        }
    }
}
