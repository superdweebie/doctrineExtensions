<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 */
final class Validator extends Annotation {

    const event = 'annotationValidator';

    const metadataKey = 'validator';

    /**
     * The FQCN of the validator to use
     * Class must implement Sds\Common\Validator\ValidatorInteface
     * or Zend\Validator\ValidatorInterface
     *
     * @var boolean
     */
    public $class;

    /**
     * An array of options to be passed to the class constructor
     *
     * @var array
     */
    public $options = array();
}