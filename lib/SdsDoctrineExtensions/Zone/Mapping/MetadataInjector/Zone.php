<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Zone\Mapping\MetadataInjector;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use SdsDoctrineExtensions\Zone\Mapping\Annotation\ZonesField as SDS_ZonesField;
use SdsDoctrineExtensions\AbstractMetadataInjector;

/**
 * Adds doNotHardDelete values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Zone extends AbstractMetadataInjector
{
    /**
     * Zone
     */
    const zonesField = 'zonesField';

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(ClassMetadataInfo $class)
    {
        $reflClass = $class->getReflectionClass();

        if (!$reflClass->implementsInterface('SdsCommon\Zone\ZoneAwareInterface')){
            return;
        }

        //Property annotations
        foreach ($reflClass->getProperties() as $property) {
            if ($class->isMappedSuperclass && !$property->isPrivate() || $class->isInheritedField($property->name)) {
                continue;
            }

            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                if ($annotation instanceof SDS_ZonesField) {
                    $class->zonesField = $property->name;
                    return;
                }
            }
        }
    }
}
