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
final class DojoValidator extends Annotation {

    const event = 'annotationDojoValidator';

    const metadataKey = 'dojoValidator';

    /**
     * The dojo module name of the validator to use
     * Class must implement sijit\Common\ValidatorInteface
     *
     * @var string
     */
    public $class;

    /**
     * An array of options to be passed to the class constructor
     *
     * @var array
     */
    public $options = array();
}