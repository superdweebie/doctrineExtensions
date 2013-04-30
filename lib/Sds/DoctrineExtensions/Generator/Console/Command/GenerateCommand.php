<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Generator\Console\Command;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;
use Sds\DoctrineExtensions\Generator\Generator;
use Symfony\Component\Console;

/**
 * Command to generate files from Doctrine document metadata.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class GenerateCommand extends Console\Command\Command
{
    /**
     * @see Console\Command\Command
     */
    protected function configure()
    {
        $this
        ->setName('sds:generate:all')
        ->setDescription('Generate files from Doctrine document metadata.')
        ->setHelp('Generate files from Doctrine document metadata.');
    }

    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $documentManager = $this->getHelper('documentManager')->getDocumentManager();

        $generator = new Generator($documentManager);
        $map = $generator->getResourceMap()->getMap();

        if (count($map) == 0){
            $output->write('Nothing to generate' . PHP_EOL);
        }

        foreach ($map as $resourceName => $config){
            $output->write(
                sprintf('Generating resource <info>%s</info>', $resourceName) . PHP_EOL
            );
            $generator->generate($resourceName);
        }
    }
}
