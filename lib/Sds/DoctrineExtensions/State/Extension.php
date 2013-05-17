<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    protected $subscribers = [
        'subscriber.state.mainsubscriber',
        'subscriber.state.annotationsubscriber',
        'subscirber.state.statePermissionSubscirber',
        'subscirber.state.transitionPermissionSubscriber'
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'subscriber.state.mainsubscriber' => 'Sds\DoctrineExtensions\State\MainSubscriber',
            'subscriber.state.annotationsubscriber' => 'Sds\DoctrineExtensions\State\AnnotationSubscriber',
            'subscirber.state.statePermissionSubscirber' => 'Sds\DoctrineExtensions\State\AccessControl\StatePermissionSubscriber',
            'subscirber.state.transitionPermissionSubscriber' => 'Sds\DoctrineExtensions\State\AccessControl\TransitionPermissionSubscriber'
        ]
    ];

    protected $filters = [
        'state' => 'Sds\DoctrineExtensions\State\Filter\State'
    ];

    protected $dependencies = [
        'extension.annotation' => true,
    ];
}
