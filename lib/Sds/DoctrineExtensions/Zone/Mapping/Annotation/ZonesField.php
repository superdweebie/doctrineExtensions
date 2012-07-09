<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Zone\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation to mark a property as the zones hash field. Property must be a
 * hash type.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class ZonesField extends Annotation
{
}