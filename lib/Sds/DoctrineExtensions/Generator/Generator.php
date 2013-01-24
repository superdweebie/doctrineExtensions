<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Generator;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 * Generate file from mapping information.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Generator implements GeneratorInterface
{

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadata $metadata
     * @return string
     */
    public function generate(ClassMetadata $metadata, DocumentManager $documentManager)
    {
        $messages = [];
        if (! $metadata->isMappedSuperclass &&
            ! $metadata->reflClass->isAbstract()
        ) {
            if (isset($metadata->generator)){
                foreach ($metadata->generator as $class){
                    $generator = new $class;
                    $messages[] = $generator->generate($metadata, $this->documentManager);
                }
            }
        }

        return implode(PHP_EOL, $messages);
    }
}
