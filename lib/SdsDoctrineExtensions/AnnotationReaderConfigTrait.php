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
trait AnnotationReaderConfigTrait {

    /**
     * @var \Doctrine\Common\Annotations\Reader
     */
    protected $annoationReader;

    /**
     *
     * @return \Doctrine\Common\Annotations\Reader
     */
    public function getAnnoationReader() {
        return $this->annoationReader;
    }

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annoationReader
     */
    public function setAnnoationReader(Reader $annoationReader) {
        $this->annoationReader = $annoationReader;
    }
}
