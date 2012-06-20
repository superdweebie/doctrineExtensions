<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Stamp\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use SdsCommon\ActiveUser\ActiveUserAwareTrait;
use SdsCommon\ActiveUser\ActiveUserAwareInterface;
use SdsCommon\User\UserInterface;

/**
 * Adds create and update stamps during persist
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractStamp implements EventSubscriber, ActiveUserAwareInterface {

    use ActiveUserAwareTrait;

    /**
     *
     * @param \SdsCommon\User\UserInterface $activeUser
     */
    public function __construct(UserInterface $activeUser) {
        $this->setActiveUser($activeUser);
    }

    protected function recomputeChangeset(LifecycleEventArgs $eventArgs) {
        $documentManager = $eventArgs->getDocumentManager();
        $document = $eventArgs->getDocument();
        $unitOfWork = $documentManager->getUnitOfWork();
        $metadata = $documentManager->getClassMetadata(get_class($document));
        $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
    }
}
