<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;

/**
 * Adds create and update stamps during persist
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractStampSubscriber implements EventSubscriber {

    protected $identityName;

    public function getIdentityName() {
        return $this->identityName;
    }

    public function setIdentityName($identityName) {
        $this->identityName = (string) $identityName;
    }

    /**
     *
     * @param string $identityName
     */
    public function __construct($identityName = null) {
        isset($identityName) ? $this->setIdentityName($identityName) : null;
    }

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
}
