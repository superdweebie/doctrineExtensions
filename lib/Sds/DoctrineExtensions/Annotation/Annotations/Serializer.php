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
 * Annotation to define behaviour of the serializer extension.
 *
 * In class context annotaions that may be placed inside are:
 *     ClassName
 *     Discriminator
 *
 * In a property context annotations that may be placed inside are:
 *     Ignore
 *
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 */
final class Serializer extends Annotation {

    const event = 'annotationSerializer';
}