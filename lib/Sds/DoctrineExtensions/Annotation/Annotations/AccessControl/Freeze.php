<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations\AccessControl;

use Doctrine\Common\Annotations\Annotation;

/**
 * When inside @AccessControl, Mark a class to be checked for access control on freeze.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 */
final class Freeze extends Annotation
{
    public $value = true;
}