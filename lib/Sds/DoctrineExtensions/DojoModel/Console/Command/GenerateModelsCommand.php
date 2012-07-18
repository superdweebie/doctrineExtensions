<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DojoModel\Console\Command;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;
use Sds\DoctrineExtensions\DojoModel\DojoModelGenerator;
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
                'dest-path', null, InputOption::VALUE_OPTIONAL,
                'The path Dojo modules should be generated to.'
            ),
            new InputOption(
                'regenerate-models', null, InputOption::VALUE_OPTIONAL,
                'Flag to define if generator should regenerate model if it exists.', true
            ),
        ))
        ->setHelp(<<<EOT
Generate Dojo modules representing Doctrine documents from your mapping information.
EOT
        );
    }

    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $documentManager = $this->getHelper('dm')->getDocumentManager();

        $metadataFactory = new ClassMetadataFactory();
        $metadataFactory->setConfiguration($documentManager->getConfiguration());
        $metadataFactory->setDocumentManager($documentManager);

        $metadatas = $metadataFactory->getAllMetadata();

        // Process destination directory
        $destPath = $input->getOption('dest-path');
        if ( ! isset($destPath)) {
            $destPath = $this->getHelper('destPath')->getDestPath();
        }

        if ( ! file_exists($destPath)) {
            throw new \InvalidArgumentException(
                sprintf("Dojo models destination directory '<info>%s</info>' does not exist.", $destPath)
            );
        } else if ( ! is_writable($destPath)) {
            throw new \InvalidArgumentException(
                sprintf("Dojo models destination directory '<info>%s</info>' does not have write permissions.", $destPath)
            );
        }

        if (count($metadatas)) {            
            
            // Create DocumentGenerator
            $generator = new DojoModelGenerator();
            $generator->setRegenerateDojoModelIfExists($input->getOption('regenerate-models'));

            foreach ($metadatas as $metadata) {
                $output->write(
                    sprintf('Processing document "<info>%s</info>"', $metadata->name) . PHP_EOL
                );
            }

            // Generating Documents
            $generator->generate($metadatas, $destPath);

            // Outputting information message
            $output->write(PHP_EOL . sprintf('Dojo models generated to "<info>%s</INFO>"', $destPath) . PHP_EOL);
        } else {
            $output->write('No Metadata Classes to process.' . PHP_EOL);
        }
    }
}
