<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Crypt;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    protected $subscribers = [
        'subscriber.crypt.mainsubscriber',
        'subscriber.crypt.annotationsubscriber'
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'subscriber.crypt.mainsubscriber' => 'Sds\DoctrineExtensions\Crypt\MainSubscriber',
            'subscriber.crypt.annotationsubscriber' => 'Sds\DoctrineExtensions\Crypt\AnnotationSubscriber'
        ]
    ];

    /**
     *
     * @var array
     */
    protected $dependencies = [
        'extension.annotation' => true
    ];
}
