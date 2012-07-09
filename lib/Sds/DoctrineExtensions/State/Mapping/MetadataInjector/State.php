<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State\Mapping\MetadataInjector;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Sds\DoctrineExtensions\State\Mapping\Annotation\StateField as SDS_StateField;
use Sds\DoctrineExtensions\AbstractMetadataInjector;

/**
 * Adds doNotHardDelete values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class State extends AbstractMetadataInjector
{
    /**
     * State
     */
    const stateField = 'stateField';

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(ClassMetadataInfo $class)
    {
        $reflClass = $class->getReflectionClass();

        if (!$reflClass->implementsInterface('Sds\Common\State\StateAwareInterface')){
            return;
        }

        //Property annotations
        foreach ($reflClass->getProperties() as $property) {
            if ($class->isMappedSuperclass && !$property->isPrivate() || $class->isInheritedField($property->name)) {
                continue;
            }

            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                if ($annotation instanceof SDS_StateField) {
                    $class->stateField = $property->name;
                    return;
                }
            }
        }
    }
}
