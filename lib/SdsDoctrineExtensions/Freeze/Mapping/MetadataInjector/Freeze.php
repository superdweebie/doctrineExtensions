<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Freeze\Mapping\MetadataInjector;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use SdsDoctrineExtensions\Freeze\Mapping\Annotation\FreezeField as SDS_FreezeField;
use SdsDoctrineExtensions\AbstractMetadataInjector;

/**
 * Adds freeze values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Freeze extends AbstractMetadataInjector
{
    /**
     * Freeze
     */
    const freezeField = 'freezeField';

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(ClassMetadataInfo $class)
    {
        $reflClass = $class->getReflectionClass();

        if (!$reflClass->implementsInterface('SdsCommon\Freeze\FreezeableInterface')){
            return;
        }
        
        //Property annotations
        foreach ($reflClass->getProperties() as $property) {
            if ($class->isMappedSuperclass && !$property->isPrivate() || $class->isInheritedField($property->name)) {
                continue;
            }

            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                if ($annotation instanceof SDS_FreezeField) {
                    $class->freezeField = $property->name;
                    return;
                }
            }
        }
    }
}
