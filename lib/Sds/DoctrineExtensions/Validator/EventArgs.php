<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Doctrine\Common\EventArgs as BaseEventArgs;
use Doctrine\ODM\MongoDB\DocumentManager;
/**
 * Arguments for invalid events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class EventArgs extends BaseEventArgs {

    /**
     * The document with the changed field
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
     * @var array
     */
    protected $messages;

    /**
     *
     * @param object $document
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager
     * @param array $messages
     */
    public function __construct(
        $document,
        DocumentManager $documentManager,
        array $messages
    ) {
        $this->document = $document;
        $this->documentManager = $documentManager;
        $this->messages = $messages;
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

    /**
     *
     * @return array
     */
    public function getMessages() {
        return $this->messages;
    }

    /**
     *
     * @param array $messages
     */
    public function setMessages(array $messages) {
        $this->messages = $messages;
    }
}