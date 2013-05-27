<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation\Annotations\Permission;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 *
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 */
final class State extends AbstractPermission
{

    const event = 'annotationStatePermission';

    public $allow;

    public $deny;

    public $state;
}