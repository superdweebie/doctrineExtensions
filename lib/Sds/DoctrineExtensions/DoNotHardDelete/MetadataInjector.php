<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DoNotHardDelete;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Sds\DoctrineExtensions\Annotations as Sds;
use Sds\DoctrineExtensions\AbstractMetadataInjector;

/**
 * Adds doNotHardDelete values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class MetadataInjector extends AbstractMetadataInjector
{
    /**
     * DoNotHardDelete
     */
    const doNotHardDelete = 'doNotHardDelete';

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(ClassMetadataInfo $class)
    {
        foreach ($this->reader->getClassAnnotations($class->reflClass) as $annot) {
            if ($annot instanceof Sds\DoNotHardDelete) {
                $class->doNotHardDelete = true;
            }
        }
    }
}
