<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Annotation;

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
        'subscriber.annotation.mainsubscriber'
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'subscriber.annotation.mainsubscriber' => 'Sds\DoctrineExtensions\Annotation\MainSubscriber',
        ],
    ];
}
