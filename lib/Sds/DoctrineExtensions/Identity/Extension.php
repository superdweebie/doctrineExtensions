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
        'subscriber.identity.annotationsubscriber',
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'subscriber.identity.annotationsubscriber' => 'Sds\DoctrineExtensions\Identity\AnnotationSubscriber',
        ]
    ];

    protected $dependencies = [
        'extension.annotation' => true,
    ];
}
