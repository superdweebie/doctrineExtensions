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
 * Arguments for credential update events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class UpdateCredentialEventArgs extends BaseEventArgs {

    protected $oldCredential;

    protected $newCredential;

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
        $oldCredential,
        $newCredential,
        $document,
        DocumentManager $documentManager
    ) {
        $this->oldCredential = $oldCredential;
        $this->newCredential = $newCredential;
        $this->document = $document;
        $this->documentManager = $documentManager;
    }

    public function getOldCredential() {
        return $this->oldCredential;
    }

    public function getNewCredential() {
        return $this->newCredential;
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