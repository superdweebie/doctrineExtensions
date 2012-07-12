<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Sds\DoctrineExtensions\AbstractMetadataInjector;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 * Adds requiresValidation values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class MetadataInjector extends AbstractMetadataInjector
{
    /**
     * uiHints
     */
    const validator = 'validator';

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(ClassMetadataInfo $class)
    {
        $reflClass = $class->getReflectionClass();

        //Class annotations
        foreach ($this->reader->getClassAnnotations($class->reflClass) as $annotation) {
            if ($annotation instanceof Sds\Validator && $this->checkAnnotation($annotation)) {
                $class->{self::validator}[$annotation->class] = $annotation->options;
                $class->requiresValidation = true;
            }
        }

        //Property annotations
        foreach ($reflClass->getProperties() as $property) {
            if ($class->isMappedSuperclass && !$property->isPrivate() || $class->isInheritedField($property->name)) {
                continue;
            }

            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                if ($annotation instanceof Sds\Validator && $this->checkAnnotation($annotation)) {
                    $class->fieldMappings[$property->getName()][self::validator][$annotation->class] = $annotation->options;
                    $class->requiresValidation = true;
                }
            }
        }
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotations\Validator $annotation
     * @return boolean
     * @throws \Exception
     */
    protected function checkAnnotation($annotation) {
        $reflection = new \ReflectionClass($annotation->class);
        $interfaces = $reflection->getInterfaceNames();
        if (!in_array('\Sds\Common\Validator\ValidatorInterface', $interfaces) &&
            !in_array('\Zend\Validator\ValidatorInterface', $interfaces)
        ) {
            throw new \Exception('Annotation validator::class must implement a ValidatorInterface');
        }
        return true;
    }
}
