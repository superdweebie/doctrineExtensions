<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\UiHints\Mapping\MetadataInjector;

use SdsDoctrineExtensions\AbstractMetadataInjector;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use SdsDoctrineExtensions\UiHints\Mapping\Annotation\UiHints as SDS_UiHints;

/**
 * Adds UiHints values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class UiHints extends AbstractMetadataInjector
{
    /**
     * uiHints
     */
    const uiHints = 'uiHints';
    
    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(ClassMetadataInfo $class)
    {
        $reflClass = $class->getReflectionClass();

        //Property annotations
        foreach ($reflClass->getProperties() as $property) {
            if ($class->isMappedSuperclass && !$property->isPrivate() || $class->isInheritedField($property->name)) {
                continue;
            }

            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                if ($annotation instanceof SDS_UiHints) {
                    $class->fieldMappings[$property->getName()][self::uiHints] = get_object_vars($annotation);
                }                
            }
        }
    }      
}
