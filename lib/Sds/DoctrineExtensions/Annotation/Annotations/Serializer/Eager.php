<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations\Serializer;

use Doctrine\Common\Annotations\Annotation;

/**
 * Shorthand for @ReferenceSerializer("Sds\DoctrineExtensions\Serializer\Reference\Eager")
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 */
final class Eager extends Annotation
{
    const event = 'annotationSerializerEager';

    public $value = true;
}