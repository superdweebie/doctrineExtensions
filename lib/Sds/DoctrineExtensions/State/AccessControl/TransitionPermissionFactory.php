<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State\AccessControl;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Sds\DoctrineExtensions\AccessControl\PermissionFactoryInterface;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class TransitionPermissionFactory implements PermissionFactoryInterface
{

    public static function get(ClassMetadata $metadata, array $options){
        return new TransitionPermission($options['roles'], $options['allow'], $options['deny'], $metadata->state);
    }
}

