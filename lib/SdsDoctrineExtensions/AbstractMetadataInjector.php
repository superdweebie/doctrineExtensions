<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;

/**
 * Base class for metadataInjection classes
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractMetadataInjector {

    /**
     * The annotation reader.
     *
     * @var Reader
     */
    protected $reader;

    /**
     * Initializes a new AnnotationDriver that uses the given Reader for reading
     * docblock annotations.
     *
     * @param $reader Reader The annotation reader to use.
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo $class
     */
    abstract public function loadMetadataForClass(ClassMetadataInfo $class);
}
