<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Doctrine\Common\Annotations\Reader;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait AnnotationReaderAwareTrait {

    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    protected $annotationReader;

    /**
     *
     * @return \Doctrine\Common\Annotations\Reader
     */
    public function getAnnotationReader() {
        return $this->annotationReader;
    }

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annoationReader
     */
    public function setAnnotationReader(Reader $annotationReader) {
        $this->annotationReader = $annotationReader;
    }
}
