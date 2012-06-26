<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\State\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation to mark a property as the state. Property must be a
 * string type.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class StateField extends Annotation
{
}