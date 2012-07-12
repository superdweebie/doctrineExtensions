<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Information about this property that the UI may find helpful when rendering.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class UiHints extends Annotation {

    /**
     * Should the UI treat this as a hidden field?
     *
     * @var boolean
     */
    public $hidden = false;

    /**
     * What should this field be called in the UI?
     *
     * @var string
     */
    public $label;

    /**
     * How wide should an input for this field be?
     *
     * @var int
     */
    public $width;

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