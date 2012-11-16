<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Annotation\EventType;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Subscriber implements EventSubscriber, AnnotationReaderAwareInterface
{
    use AnnotationReaderAwareTrait;

    /**
     *
     * @var \Sds\Common\Validator\ValidatorInterface
     */
    protected $documentValidator;

    protected $validateOnFlush;

    /**
     *
     * @return \Sds\Common\Validator\ValidatorInterface
     */
    public function getDocumentValidator() {
        return $this->documentValidator;
    }

    /**
     *
     * @param \Sds\Common\Validator\ValidatorInterface $validator
     */
    public function setDocumentValidator(DocumentValidatorInterface $documentValidator) {
        $this->documentValidator = $documentValidator;
    }

    public function getValidateOnFlush() {
        return $this->validateOnFlush;
    }

    public function setValidateOnFlush($validateOnFlush) {
        $this->validateOnFlush = (boolean) $validateOnFlush;
    }

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        $events = array(
            Sds\AlphaValidator::event,
            Sds\CredentialValidator::event,
            Sds\CurrencyValidator::event,
            Sds\EmailAddressValidator::event,
            Sds\IdentifierArrayValidator::event,
            Sds\IdentifierValidator::event,
            Sds\InequalityValidator::event,
            Sds\LengthValidator::event,
            Sds\NotRequiredValidator::event,
            Sds\PersonalNameValidator::event,
            Sds\RequiredValidator::event,
            Sds\Validator::event,
        );
        if ($this->getValidateOnFlush()) {
            $events[] = ODMEvents::onFlush;
        }
        return $events;
    }

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     * @param \Sds\Common\Validator\ValidatorInterface $validator
     */
    public function __construct(
        Reader $annotationReader,
        DocumentValidatorInterface $documentValidator,
        $validateOnFlush
    ){
        $this->setAnnotationReader($annotationReader);
        $this->setDocumentValidator($documentValidator);
        $this->setValidateOnFlush($validateOnFlush);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationAlphaValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Common\Validator\AlphaValidator']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationCredentialValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Common\Validator\CredentialValidator']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationCurrencyValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Common\Validator\CurrencyValidator']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationEmailAddressValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Common\Validator\EmailAddressValidator']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationIdentifierArrayValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Common\Validator\IdentifierArrayValidator']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationIdentifierValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Common\Validator\IdentifierValidator']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationInequalityValidator(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $this->addFieldValidator($eventArgs, [
            'class' => 'Sds\Common\Validator\InequalityValidator',
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
            'class' => 'Sds\Common\Validator\LengthValidator',
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
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Common\Validator\NotRequiredValidator']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationPersonalNameValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Common\Validator\PersonalNameValidator']);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationRequiredValidator(AnnotationEventArgs $eventArgs)
    {
        $this->addFieldValidator($eventArgs, ['class' => 'Sds\Common\Validator\RequiredValidator']);
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
            case EventType::property:
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

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs  $eventArgs)
    {
        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();

        foreach ($unitOfWork->getScheduledDocumentUpdates() AS $document) {
            $metadata = $documentManager->getClassMetadata(get_class($document));

            $validatorResult = $this->documentValidator->isValid($document, $metadata);
            if ( ! $validatorResult->getResult()) {

                // Updates to invalid documents are not allowed. Roll them back
                $unitOfWork->clearDocumentChangeSet(spl_object_hash($document));

                $eventManager = $documentManager->getEventManager();

                // Raise invalidUpdate
                if ($eventManager->hasListeners(Events::invalidUpdate)) {
                    $eventManager->dispatchEvent(
                        Events::invalidUpdate,
                        new EventArgs($document, $documentManager, $validatorResult->getMessages())
                    );
                }
            }
        }

        foreach ($unitOfWork->getScheduledDocumentInsertions() as $document) {
            $metadata = $documentManager->getClassMetadata(get_class($document));

            $validatorResult = $this->documentValidator->isValid($document, $metadata);
            if ( ! $validatorResult->getResult()) {

                //stop creation
                $unitOfWork->detach($document);

                $eventManager = $documentManager->getEventManager();

                // Raise invalidUpdate
                if ($eventManager->hasListeners(Events::invalidCreate)) {
                    $eventManager->dispatchEvent(
                        Events::invalidCreate,
                        new EventArgs($document, $documentManager, $validatorResult->getMessages())
                    );
                }
            }
        }
    }
}
