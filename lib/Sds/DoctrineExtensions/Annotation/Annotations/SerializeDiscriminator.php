<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Add the discriminator field to serialization, if it exists
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"CLASS"})
 */
final class SerializeDiscriminator extends Annotation
{
    const event = 'annotationSerializeDiscriminator';

    const metadataKey = 'serializeDiscriminator';
}