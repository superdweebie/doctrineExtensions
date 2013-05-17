<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Adds create and update stamps during persist
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractStampSubscriber implements EventSubscriber, ServiceLocatorAwareInterface {

    use ServiceLocatorAwareTrait;

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $eventArgs
     */
    protected function recomputeChangeset(LifecycleEventArgs $eventArgs) {
        $documentManager = $eventArgs->getDocumentManager();
        $document = $eventArgs->getDocument();
        $unitOfWork = $documentManager->getUnitOfWork();
        $metadata = $documentManager->getClassMetadata(get_class($document));
        $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
    }

    protected function getIdentityName(){
        return $this->serviceLocator->get('identity')->getIdentityName();
    }
}
