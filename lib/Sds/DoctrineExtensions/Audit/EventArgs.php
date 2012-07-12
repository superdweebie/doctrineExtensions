<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Audit;

use Doctrine\Common\EventArgs as BaseEventArgs;
use Doctrine\ODM\MongoDB\DocumentManager;
use Sds\Common\Audit\AuditInterface;

/**
 * Arguments for readonly events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class EventArgs extends BaseEventArgs {

    /**
     *
     * @var \Sds\Common\Audit\AuditInterface
     */
    protected $audit;

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
     * @param \Sds\Common\Audit\AuditInterface $audit
     * @param object $document
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager
     */
    public function __construct(
        AuditInterface $audit,
        $document,
        DocumentManager $documentManager
    ) {
        $this->audit = $audit;
        $this->document = $document;
        $this->documentManager = $documentManager;
    }

    /**
     *
     * @return \Sds\Common\Audit\AuditInterface
     */
    public function getAudit() {
        return $this->audit;
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