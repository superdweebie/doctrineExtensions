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
        'Sds\DoctrineExtensions\Validator\MainSubscriber',
        'Sds\DoctrineExtensions\Validator\AnnotationSubscriber'
    ];

    protected $dependencies = [
        'Sds\DoctrineExtensions\Annotation' => true,
    ];

    protected $defaultServiceManagerConfig = [
        'invokables' => [
            'documentValidator' => 'Sds\DoctrineExtensions\Validator\DocumentValidator'
        ]
    ];
}
