<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Readonly\Event;

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
     * Name of the field which has been changed
     *
     * @var string
     */
    protected $field;

    /**
     * The field value before it was changed
     *
     * @var mixed
     */
    protected $originalValue;

    /**
     * The field value after it was changed
     *
     * @var mixed
     */
    protected $newValue;

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
     * @param string $field
     * @param mixed $originalValue
     * @param mixed $newValue
     * @param object $document
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager
     */
    public function __construct(
        $field,
        $originalValue,
        $newValue,
        $document,
        DocumentManager $documentManager
    ) {
        $this->field = $field;
        $this->originalValue = $originalValue;
        $this->newValue = $newValue;
        $this->document = $document;
        $this->documentManager = $documentManager;
    }

    /**
     *
     * @return string
     */
    public function getField() {
        return $this->field;
    }

    /**
     *
     * @return mixed
     */
    public function getOriginalValue() {
        return $this->originalValue;
    }

    /**
     *
     * @return mixed
     */
    public function getNewValue() {
        return $this->newValue;
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