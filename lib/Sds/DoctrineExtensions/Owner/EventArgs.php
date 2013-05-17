<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Owner;

use Doctrine\Common\EventArgs as BaseEventArgs;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Arguments for owner update events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class EventArgs extends BaseEventArgs {

    protected $oldOwner;

    protected $newOwner;

    /**
     * The document with the changed roles
     *
     * @var object
     */
    protected $document;

    /**
     *
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $documentManager;

    /**
     *
     * @param \Sds\Common\State\Transition $transition
     * @param object $document
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager
     */
    public function __construct(
        $oldOwner,
        $newOwner,
        $document,
        DocumentManager $documentManager
    ) {
        $this->oldOwner = $oldOwner;
        $this->newOwner = $newOwner;
        $this->document = $document;
        $this->documentManager = $documentManager;
    }

    public function getOldOwner() {
        return $this->oldOwner;
    }

    public function getNewOwner() {
        return $this->newOwner;
    }

    /**
     *
     * @return object
     */
    public function getDocument() {
        return $this->document;
    }

    /**
     *
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getDocumentManager() {
        return $this->documentManager;
    }
}