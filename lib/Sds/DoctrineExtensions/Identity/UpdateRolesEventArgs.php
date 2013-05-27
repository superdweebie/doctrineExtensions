<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Identity;

use Doctrine\Common\EventArgs as BaseEventArgs;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Arguments for roles update events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class UpdateRolesEventArgs extends BaseEventArgs {

    protected $oldRoles;

    protected $newRoles;

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

    public function __construct(
        $oldRoles,
        $newRoles,
        $document,
        DocumentManager $documentManager
    ) {
        $this->oldRoles = $oldRoles;
        $this->newRoles = $newRoles;
        $this->document = $document;
        $this->documentManager = $documentManager;
    }

    public function getOldRoles() {
        return $this->oldRoles;
    }

    public function getNewRoles() {
        return $this->newRoles;
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