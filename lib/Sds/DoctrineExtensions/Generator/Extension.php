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

    protected $defaultServiceManagerConfig = [
        'invokables' => [
            'resourceMap' => 'Sds\DoctrineExtensions\Generator\ResourceMap',
            'generator' => 'Sds\DoctrineExtensions\Generator\Generator'
        ]
    ];

    protected $cliCommands = [
        'Sds\DoctrineExtensions\Generator\Console\Command\GenerateCommand'
    ];

    public function getCliCommands(){
        foreach ($this->cliCommands as $key => $command){
            if (is_string($command)){
                $this->cliCommands[$key] = new $command;
            }
        }

        return $this->cliCommands;
    }
}