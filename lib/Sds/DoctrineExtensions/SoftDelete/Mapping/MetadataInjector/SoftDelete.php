<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete\Mapping\MetadataInjector;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Sds\DoctrineExtensions\SoftDelete\Mapping\Annotation\SoftDeleteField as SDS_SoftDeleteField;
use Sds\DoctrineExtensions\AbstractMetadataInjector;

/**
 * Adds doNotHardDelete values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class SoftDelete extends AbstractMetadataInjector
{
    /**
     * SoftDelete
     */
    const softDeleteField = 'softDeleteField';

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(ClassMetadataInfo $class)
    {
        $reflClass = $class->getReflectionClass();

        if (!$reflClass->implementsInterface('Sds\Common\SoftDelete\SoftDeleteableInterface')){
            return;
        }
        
        //Property annotations
        foreach ($reflClass->getProperties() as $property) {
            if ($class->isMappedSuperclass && !$property->isPrivate() || $class->isInheritedField($property->name)) {
                continue;
            }

            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                if ($annotation instanceof SDS_SoftDeleteField) {
                    $class->softDeleteField = $property->name;
                    return;
                }
            }
        }
    }
}
