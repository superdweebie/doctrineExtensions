<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Readonly\Mapping\MetadataInjector;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use SdsDoctrineExtensions\Readonly\Mapping\Annotation\Readonly as SDS_Readonly;

class Readonly
{
    /**
     * Readonly
     */
    const READONLY = 'readonly';

    /**
     * The annotation reader.
     *
     * @var Reader
     */
    private $reader;

    /**
     * Initializes a new AnnotationDriver that uses the given Reader for reading
     * docblock annotations.
     *
     * @param $reader Reader The annotation reader to use.
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

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
                if ($annot instanceof SDS_Readonly) {
                    $setMethod = $annot->setMethod;
                    if ($annot->setMethod == 'set*'){
                        $setMethod = 'set' . $property->getName();
                    }                    
                    $class->fieldMappings[$property->getName()][self::READONLY] = array(
                        'value' => $annot->value,
                        'setMethod' => $setMethod
                    );
                }
            }
        }
    }
}
