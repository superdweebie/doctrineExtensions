<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Rest;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    protected $subscribers = [
        'subscriber.rest.annotationsubscriber'
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'endpointMap' => 'Sds\DoctrineExtensions\Rest\EndpointMap',
            'subscriber.rest.annotationsubscriber' => 'Sds\DoctrineExtensions\Rest\AnnotationSubscriber'
        ]
    ];

    /**
     *
     * @var array
     */
    protected $dependencies = array(
        'extension.annotation' => true,
        'extension.reference' => true
    );
}
