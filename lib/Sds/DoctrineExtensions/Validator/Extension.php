<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

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
        'subscriber.validator.mainsubscriber',
        'subscriber.validator.annotationsubscriber'
    ];

    protected $dependencies = [
        'extension.annotation' => true,
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'documentValidator' => 'Sds\DoctrineExtensions\Validator\DocumentValidator',
            'subscriber.validator.mainsubscriber' => 'Sds\DoctrineExtensions\Validator\MainSubscriber',
            'subscriber.validator.annotationsubscriber' => 'Sds\DoctrineExtensions\Validator\AnnotationSubscriber'
        ]
    ];
}
