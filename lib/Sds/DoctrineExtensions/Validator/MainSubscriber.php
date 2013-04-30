<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\DoctrineExtensions\AbstractLazySubscriber;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class MainSubscriber extends AbstractLazySubscriber implements ServiceLocatorAwareInterface {

    use ServiceLocatorAwareTrait;

    /**
     *
     * @var \Sds\Validator\ValidatorInterface
     */
    protected $documentValidator;

    /**
     *
     * @return array
     */
    public static function getStaticSubscribedEvents(){
        $events = [
            ODMEvents::onFlush
        ];
        return $events;
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs  $eventArgs)
    {
        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();
        $documentValidator = $this->getDocumentValidator();

        foreach ($unitOfWork->getScheduledDocumentUpdates() as $document) {
            $metadata = $documentManager->getClassMetadata(get_class($document));

            $validatorResult = $documentValidator->isValid($document, $metadata);
            if ( ! $validatorResult->getResult()) {

                // Updates to invalid documents are not allowed. Roll them back
                $unitOfWork->detach($document);

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

            $validatorResult = $documentValidator->isValid($document, $metadata);
            if ( ! $validatorResult->getResult()) {

                //stop creation
                $unitOfWork->detach($document);

                $eventManager = $documentManager->getEventManager();

                // Raise invalidCreate
                if ($eventManager->hasListeners(Events::invalidCreate)) {
                    $eventManager->dispatchEvent(
                        Events::invalidCreate,
                        new EventArgs($document, $documentManager, $validatorResult->getMessages())
                    );
                }
            }
        }
    }

    /**
     *
     * @return \Sds\Validator\ValidatorInterface
     */
    protected function getDocumentValidator() {
        if ( !isset($this->documentValidator)){
            $this->documentValidator = $this->serviceLocator->get('documentValidator');
        }
        return $this->documentValidator;
    }
}
