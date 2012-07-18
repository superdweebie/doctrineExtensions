<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Add the discriminator field to generated Dojo model, if it exists
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"CLASS"})
 */
final class DojoDiscriminator extends Annotation
{
    const event = 'annotationDojoDiscriminator';

    const metadataKey = 'dojoDiscriminator';
}