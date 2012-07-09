<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl\Mapping\MetadataInjector;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Sds\DoctrineExtensions\AccessControl\Mapping\Annotation\Audit as SDS_DoNotAccessControlUpdate;
use Sds\DoctrineExtensions\AbstractMetadataInjector;

/**
 * Adds ignore access control update values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AccessControl extends AbstractMetadataInjector
{
    /**
     * doNotAccessControlUpdate
     */
    const doNotAccessControlUpdate = 'doNotAccessControlUpdate';

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

            foreach ($this->reader->getPropertyAnnotations($property) as $annot) {
                if ($annot instanceof SDS_DoNotAccessControlUpdate) {
                    $class->fieldMappings[$property->getName()][self::doNotAccessControlUpdate] = true;
                }
            }
        }
    }
}
