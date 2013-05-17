<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

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
        'subscriber.accessControl.mainsubscriber',
        'subscriber.accessControl.annotationsubscriber',
        'subscriber.accessControl.basicPermissionSubscriber'
    ];

    protected $filters = [
        'readAccessControl' => 'Sds\DoctrineExtensions\AccessControl\Filter\ReadAccessControl'
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'subscriber.accessControl.mainsubscriber' => 'Sds\DoctrineExtensions\AccessControl\MainSubscriber',
            'subscriber.accessControl.annotationsubscriber' => 'Sds\DoctrineExtensions\AccessControl\AnnotationSubscriber',
            'subscriber.accessControl.basicPermissionSubscriber' => 'Sds\DoctrineExtensions\AccessControl\BasicPermissionSubscriber',
            'accessController' => 'Sds\DoctrineExtensions\AccessControl\AccessController'
        ]
    ];

    /**
     *
     * @var array
     */
    protected $dependencies = [
        'extension.annotation' => true,
        'extension.identity' => true,
    ];
}
