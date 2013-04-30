<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension
{
    protected $dependencies = [
        'Sds\DoctrineExtensions\Annotation' => true,
        'Sds\DoctrineExtensions\Identity' => true,
    ];

    protected $subscribers = [
        'Sds\DoctrineExtensions\Freeze\MainSubscriber',
        'Sds\DoctrineExtensions\Freeze\StampSubscriber',
        'Sds\DoctrineExtensions\Freeze\AnnotationSubscriber',
        'Sds\DoctrineExtensions\Freeze\AccessControl\FreezeSubscriber'
    ];

    protected $filters = [
        'freeze' => 'Sds\DoctrineExtensions\Freeze\Filter\Freeze'
    ];

    protected $defaultServiceManagerConfig = [
        'invokables' => [
            'freezer' => 'Sds\DoctrineExtensions\Freeze\Freezer'
        ]
    ];
}
