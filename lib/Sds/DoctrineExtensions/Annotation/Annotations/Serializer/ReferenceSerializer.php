<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations\Serializer;

use Doctrine\Common\Annotations\Annotation;

/**
 * Define the serializer that should be used to serialize a reference document. Must be used in the context
 * of the Serializer annotation
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 */
final class ReferenceSerializer extends Annotation
{
    const event = 'annotationSerializerReferenceSerializer';

    public $value;
}