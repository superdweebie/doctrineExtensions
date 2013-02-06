<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl\UpdatePermissions;

use Doctrine\Common\EventArgs as BaseEventArgs;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Arguments for permissions update events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class EventArgs extends BaseEventArgs {

    protected $stopUpdatePermissions = false;

    protected $oldCollection;

    protected $newCollection;

    /**
     * The document with the changed permissions
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
        $oldCollection,
        $newCollection,
        $document,
        DocumentManager $documentManager
    ) {
        $this->oldCollection = $oldCollection;
        $this->newCollection = $newCollection;
        $this->document = $document;
        $this->documentManager = $documentManager;
    }

    public function getStopUpdatePermissions() {
        return $this->stopUpdatePermissions;
    }

    public function setStopUpdatePermissions($stopUpdatePermissions) {
        $this->stopUpdatePermissions = (boolean) $stopUpdatePermissions;
    }

    public function getOldCollection() {
        return $this->oldCollection;
    }

    public function getNewCollection() {
        return $this->newCollection;
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