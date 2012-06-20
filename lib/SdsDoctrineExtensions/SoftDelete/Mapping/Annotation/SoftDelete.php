<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation to mark a property as the soft delete flag. Property must be a
 * boolean type.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class SoftDelete extends Annotation
{
    public $getMethod = 'get*';
}