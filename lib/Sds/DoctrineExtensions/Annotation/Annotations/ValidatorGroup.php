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
 * Defines an array of validators. This annotation is valid in the following contexts
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
 * Other annotations are also permitted. If other annotations are present, they must have an event
 * constant defined. This event will be triggered in the doctrine event manager, and a AnnotationEventArgs object passed.
 *
 * @Annotation
 */
final class ValidatorGroup extends Annotation {

    const event = 'annotationValidatorGroup';

    public $value = [];
}