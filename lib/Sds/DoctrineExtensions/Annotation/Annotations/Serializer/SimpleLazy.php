<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations\Serializer;

use Doctrine\Common\Annotations\Annotation;

/**
 * Shorthand for @ReferenceSerializer("Sds\DoctrineExtensions\Serializer\Reference\SimpleLazy")
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 */
final class SimpleLazy extends Annotation
{
    const event = 'annotationSerializerSimpleLazy';

    public $value = true;
}