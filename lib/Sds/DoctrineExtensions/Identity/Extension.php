<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Identity;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension
{

    protected $subscribers = [
        'Sds\DoctrineExtensions\Identity\MainSubscriber',
        'Sds\DoctrineExtensions\Identity\AnnotationSubscriber',
        'Sds\DoctrineExtensions\Identity\AccessControl\UpdateRolesSubscriber'
    ];

    protected $dependencies = [
        'Sds\DoctrineExtensions\Annotation' => true,
    ];
}
