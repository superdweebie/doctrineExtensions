<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DoNotHardDelete\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation to mark a class as impossible to hard delete (soft delete still permitted)
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"CLASS"})
 */
final class DoNotHardDelete extends Annotation
{
}