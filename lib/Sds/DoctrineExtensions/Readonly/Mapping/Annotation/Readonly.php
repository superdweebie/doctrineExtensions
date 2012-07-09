<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Readonly\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation to mark fields as readonly
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class Readonly extends Annotation
{
    public $setMethod = 'set*';
}