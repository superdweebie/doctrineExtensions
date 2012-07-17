<?php

namespace Sds\DoctrineExtensions\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console;

/**
 * Command to generate Dojo modules representing Doctrine documents from your mapping information.
 *
 */
class GenerateModelsCommand extends Console\Command\Command
{
    /**
     * @see Console\Command\Command
     */
    protected function configure()
    {
        $this
        ->setName('odm:generate:dojoModels')
        ->setDescription('Generate Dojo modules representing Doctrine documents from your mapping information.')
        ->setDefinition(array(
            new InputArgument(
                'dest-path', InputArgument::REQUIRED, 'The path to generate your document classes.'
            ),
        ))
        ->setHelp(<<<EOT
Generate Dojo modules representing Doctrine documents from your mapping information.
EOT
        );
    }

    /**
     * @see Console\Command\Command
     */
    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $output->write('Works');
    }
}
