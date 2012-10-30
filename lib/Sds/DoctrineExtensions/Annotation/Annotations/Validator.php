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
 * Defines a validator to be used.
 *
 * May be an an array of validators. This annotation is valid in the following contexts
 *     As a class annotation
 *         It will defined the class validators used by the validator extension
 *
 *     As a property annotation
 *         It will define the the property validators used by the validator extension
 *
 *     As an annotation inside a dojo annotation
 *         It will define the validators generated in the Dojo Model metadata
 *
 * The validator array must be an array of annotations. The following annotations are valid
 *     Validator
 *     Required
 *
 *
 * @Annotation
 */
final class Validator extends Annotation {

    const event = 'annotationValidator';

    /**
     * The FQCN of the validator to use
     * Class must implement Sds\Common\Validator\ValidatorInteface
     *
     * In the context of a Dojo annotation, class must be
     * the a module name. The module must inherit from
     * the Sds\Common\Validator\BaseValidator module
     *
     * @var string
     */
    public $class;

    /**
     * An array of options to be passed to the class constructor
     *
     * @var array
     */
    public $options = [];

    public $value = [];
}