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
 * @Target({"PROPERTY"})
 */
final class PropertyDojo extends Annotation {

    const event = 'annotationPropertyDojo';

    const metadataKey = 'propertyDojo';

    public $validators;

    public $required;

    /**
     * What type of input should the UI use?
     *
     * @var boolean
     */
    public $inputType;

    /**
     * What should this field be called in the UI?
     *
     * @var string
     */
    public $title;

    /**
     * Short description of this field.
     *
     * @var string
     */
    public $tooltip;

    /**
     * Long description of this field
     *
     * @var string
     */
    public $description;
}