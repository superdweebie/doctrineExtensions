<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Stamp;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Sds\Common\User\ActiveUserAwareTrait;
use Sds\Common\User\ActiveUserAwareInterface;
use Sds\Common\User\UserInterface;

/**
 * Adds create and update stamps during persist
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractStampSubscriber implements EventSubscriber, ActiveUserAwareInterface {

    use ActiveUserAwareTrait;

    /**
     *
     * @param \Sds\Common\User\UserInterface $activeUser
     */
    public function __construct(UserInterface $activeUser) {
        $this->setActiveUser($activeUser);
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
