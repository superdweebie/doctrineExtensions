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
        'extension.annotation' => true,
        'extension.identity' => true,
    ];

    protected $subscribers = [
        'subscriber.freeze.mainsubscriber',
        'subscriber.freeze.stampsubscriber',
        'subscriber.freeze.annotationsubscriber',
        'subscriber.freeze.freezesubscriber'
    ];

    protected $filters = [
        'freeze' => 'Sds\DoctrineExtensions\Freeze\Filter\Freeze'
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'freezer' => 'Sds\DoctrineExtensions\Freeze\Freezer',
            'subscriber.freeze.mainsubscriber' => 'Sds\DoctrineExtensions\Freeze\MainSubscriber',
            'subscriber.freeze.stampsubscriber' => 'Sds\DoctrineExtensions\Freeze\StampSubscriber',
            'subscriber.freeze.annotationsubscriber' => 'Sds\DoctrineExtensions\Freeze\AnnotationSubscriber',
            'subscriber.freeze.freezesubscriber' => 'Sds\DoctrineExtensions\Freeze\AccessControl\FreezeSubscriber'
        ]
    ];
}
