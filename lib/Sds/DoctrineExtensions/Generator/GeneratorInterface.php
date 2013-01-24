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
interface GeneratorInterface
{

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadata $metadata
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager
     * @return string
     */
    public function generate(ClassMetadata $metadata, DocumentManager $documentManager);

}
