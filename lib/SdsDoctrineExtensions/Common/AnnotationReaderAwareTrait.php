<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Common;

use Doctrine\Common\Annotations\Reader;

/**
 * Implementation of AnnotationReaderAwareInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait AnnotationReaderAwareTrait {

    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    protected $annoationReader;

    /**
     *
     * @param Reader $annotationReader
     */
    public function setReader(Reader $annotationReader){
        $this->annotationReader = $annotationReader;
    }
}