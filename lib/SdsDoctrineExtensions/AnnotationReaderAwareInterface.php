<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions;

use Doctrine\Common\Annotations\Reader;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
interface AnnotationReaderAwareInterface {

    /**
     *
     * @return \Doctrine\Common\Annotations\Reader
     */
    public function getAnnotationReader();

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annoationReader
     */
    public function setAnnotationReader(Reader $annotationReader);
}
