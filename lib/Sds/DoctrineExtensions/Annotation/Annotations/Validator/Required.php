<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations\Validator;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation to mark a property as required
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class Required extends Annotation
{
    const event = 'annotationRequiredValidator';

    public $value = true;
}