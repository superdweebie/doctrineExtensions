<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Indicates that updates to a field should be ignored when checking update permission
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class DoNotAccessControlUpdate extends Annotation
{
}