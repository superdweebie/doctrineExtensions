<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions;

use Doctrine\Common\Annotations\Reader;

/**
 * Use on a class that requires an annotation reader
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
interface AnnotationReaderAwareInterface {

    /**
     * @param \Doctrine\Common\Annotations\Reader $annoationReader
     */
    public function setAnnotationReader(Reader $annoationReader);
}
