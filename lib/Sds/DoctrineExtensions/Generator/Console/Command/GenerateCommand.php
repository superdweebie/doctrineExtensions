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
        ->setName('odm:generate:doctrineExtensions')
        ->setDescription('Generate files from Doctrine document metadata.')
        ->setHelp(<<<EOT
Generate files from Doctrine document metadata.
EOT
        );
    }

    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $documentManager = $this->getHelper('documentManager')->getDocumentManager();

        $metadataFactory = new ClassMetadataFactory();
        $metadataFactory->setConfiguration($documentManager->getConfiguration());
        $metadataFactory->setDocumentManager($documentManager);

        $metadatas = $metadataFactory->getAllMetadata();

        // Create DocumentGenerator
        $generator = new Generator();
        $documentManager = $this->getHelper('dm')->getDocumentManager();

        if (count($metadatas)) {

            foreach ($metadatas as $metadata) {
                $output->write(
                    sprintf('Processing document "<info>%s</info>"', $metadata->name) . PHP_EOL
                );
                $output->write($generator->generate($metadata, $documentManager) . PHP_EOL);
            }
        } else {
            $output->write('No Metadata to process.' . PHP_EOL);
        }
    }
}
