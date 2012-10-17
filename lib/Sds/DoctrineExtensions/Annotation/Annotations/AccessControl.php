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
 * Annotation to define behaviour of the access control extension.
 *
 * In class context annotaions that may be placed inside are:
 *     Create
 *     Read
 *     Update
 *     Delete
 *     State
 *
 * In a property context annotations that may be placed inside are:
 *     Update
 *
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 */
final class AccessControl extends Annotation {

    const event = 'annotationAccessControl';
}