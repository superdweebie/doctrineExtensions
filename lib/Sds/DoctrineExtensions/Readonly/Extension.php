<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Readonly;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    protected $subscribers = [
        'subscriber.readonly.mainsubscriber',
        'subscriber.readonly.annotationsubscriber',
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'subscriber.readonly.mainsubscriber' => 'Sds\DoctrineExtensions\Readonly\MainSubscriber',
            'subscriber.readonly.annotationsubscriber' => 'Sds\DoctrineExtensions\Readonly\AnnotationSubscriber',
        ]
    ];

    /**
     *
     * @var array
     */
    protected $dependencies = [
        'extension.annotation' => true,
    ];
}
