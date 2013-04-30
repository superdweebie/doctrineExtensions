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
        'Sds\DoctrineExtensions\Rest\AnnotationSubscriber'
    ];

    protected $defaultServiceManagerConfig = [
        'invokables' => [
            'endpointMap' => 'Sds\DoctrineExtensions\Rest\EndpointMap'
        ]
    ];

    /**
     *
     * @var array
     */
    protected $dependencies = array(
        'Sds\DoctrineExtensions\Annotation' => true
    );
}
