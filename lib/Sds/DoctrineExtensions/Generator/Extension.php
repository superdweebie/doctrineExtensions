<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Generator;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    protected $serviceManagerConfig = [
        'invokables' => [
            'resourceMap' => 'Sds\DoctrineExtensions\Generator\ResourceMap',
            'cli.generate' => 'Sds\DoctrineExtensions\Generator\Console\Command\GenerateCommand'
        ],
        'factories' => [
            'generator' => 'Sds\DoctrineExtensions\Generator\GeneratorFactory'
        ]
    ];

    protected $cliCommands = [
        'generate'
    ];
}