<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Generator;

use Doctrine\Common\EventArgs as BaseEventArgs;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Arguments for generate events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class GenerateEventArgs extends BaseEventArgs {

    protected $resourceName;

    protected $className;

    protected $documentManager;

    protected $options;

    protected $resource;

    public function __construct(
        $resourceName,
        $className,
        DocumentManager $documentManager,
        array $options = [],
        \stdClass $resource
    ) {
        $this->resourceName = $resourceName;
        $this->className = $className;
        $this->documentManager = $documentManager;
        $this->options = $options;
        $this->resource = $resource;
    }

    public function getResourceName() {
        return $this->resourceName;
    }

    public function getClassName() {
        return $this->className;
    }

    public function getDocumentManager() {
        return $this->documentManager;
    }

    public function getOptions() {
        return $this->options;
    }

    public function getResource() {
        return $this->resource;
    }

}