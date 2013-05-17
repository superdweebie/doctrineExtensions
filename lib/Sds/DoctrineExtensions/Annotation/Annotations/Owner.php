<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation to mark a field as the owner field. Field must be a
 * string type.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class Owner extends Annotation
{
    const event = 'annotationOwner';
}