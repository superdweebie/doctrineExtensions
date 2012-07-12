<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State;

use Doctrine\Common\EventArgs as BaseEventArgs;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Arguments for readonly events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class EventArgs extends BaseEventArgs {

    /**
     *
     * @var state
     */
    protected $fromState;

    /**
     *
     * @var state
     */
    protected $toState;

    /**
     * The document with the changed state
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
     * @param string $fromState
     * @param string $toState
     * @param object $document
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager
     */
    public function __construct(
        $fromState,
        $toState,
        $document,
        DocumentManager $documentManager
    ) {
        $this->fromState = (string) $fromState;
        $this->toState = (string) $toState;
        $this->document = $document;
        $this->documentManager = $documentManager;
    }

    /**
     *
     * @return string
     */
    public function getFromState() {
        return $this->fromState;
    }

    /**
     *
     * @return string
     */
    public function getToState() {
        return $this->toState;
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