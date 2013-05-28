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

    protected $resourceMap;

    protected $serviceManagerConfig = [
        'invokables' => [
            'cli.generate' => 'Sds\DoctrineExtensions\Generator\Console\Command\GenerateCommand'
        ],
        'factories' => [
            'resourceMap' => 'Sds\DoctrineExtensions\Generator\ResourceMapFactory',
        ]
    ];

    protected $cliCommands = [
        'generate'
    ];

    public function getResourceMap() {
        return $this->resourceMap;
    }

    public function setResourceMap($resourceMap) {
        $this->resourceMap = $resourceMap;
    }
}