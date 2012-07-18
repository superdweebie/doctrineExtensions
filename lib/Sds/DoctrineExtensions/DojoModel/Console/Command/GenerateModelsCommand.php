<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DojoModel\Console\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console;

/**
 * Command to generate Dojo modules representing Doctrine documents from your mapping information.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
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
            new InputOption(
                'dest-path', null, InputOption::VALUE_REQUIRED,
                'The path Dojo modules should be generated to.'
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
