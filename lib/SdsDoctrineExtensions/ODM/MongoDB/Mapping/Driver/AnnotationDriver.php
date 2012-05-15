<?php

namespace SdsDoctrineExtensions\ODM\MongoDB\Mapping\Driver;

use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver as MongoDBAnnotationDriver,
    Doctrine\ODM\MongoDB\Mapping\Driver\Driver,
    Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\Common\Annotations\AnnotationRegistry,
    Doctrine\Common\Annotations\Reader,
    Doctrine\ODM\MongoDB\Events,
    Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo,
    Doctrine\ODM\MongoDB\MongoDBException,
    SdsDoctrineExtensions\ODM\MongoDB\Mapping\Annotation as SDS;

class AnnotationDriver implements Driver
{
    
    /**
     * The annotation reader.
     *
     * @var Reader
     */
    private $reader;

    /**
     * The paths where to look for mapping files.
     *
     * @var array
     */
    private $paths = array();

    /**
     * The file extension of mapping documents.
     *
     * @var string
     */
    private $fileExtension = '.php';

    /**
     * @param array
     */
    private $classNames;

    private $mongoDBAnnotationDriver;
    
    /**
     * Initializes a new AnnotationDriver that uses the given Reader for reading
     * docblock annotations.
     *
     * @param $reader Reader The annotation reader to use.
     * @param string|array $paths One or multiple paths where mapping classes can be found.
     */
    public function __construct(Reader $reader, $paths = null)
    {
        $this->reader = $reader;
        if ($paths) {
            $this->addPaths((array) $paths);
        }
        $this->mongoDBAnnotationDriver = new MongoDBAnnotationDriver($reader, $paths);
    }

    /**
     * Append lookup paths to metadata driver.
     *
     * @param array $paths
     */
    public function addPaths(array $paths)
    {
        $this->paths = array_unique(array_merge($this->paths, $paths));
    }    
    
    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass($className, ClassMetadataInfo $class)
    {
        $this->mongoDBAnnotationDriver->loadMetadataForClass($className, $class);

        $reflClass = $class->getReflectionClass();

        //Class annotations
        foreach ($this->reader->getClassAnnotations($reflClass) as $annot) {
        }

        //Property annotations
        foreach ($reflClass->getProperties() as $property) {
            if ($class->isMappedSuperclass && !$property->isPrivate() || $class->isInheritedField($property->name)) {
                continue;
            }

            foreach ($this->reader->getPropertyAnnotations($property) as $annot) {
                if ($annot instanceof SDS\Audited) {
                    $class->fieldMappings[$property->getName()][audited] = true;
                }
            }
        }

        //Method annotations
        foreach ($reflClass->getMethods() as $method) {
            if ($method->isPublic()) {
                foreach ($this->reader->getMethodAnnotations($method) as $annot) {
                }
            }
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAllClassNames()
    {
        return $this->mongoDBAnnotationDriver->getAllClassNames();
    }    
    /**
     * Whether the class with the specified name is transient. Only non-transient
     * classes, that is entities and mapped superclasses, should have their metadata loaded.
     * A class is non-transient if it is annotated with either @Entity or
     * @MappedSuperclass in the class doc block.
     *
     * @param string $className
     * @return boolean
     */
    public function isTransient($className)
    {
        return $this->mongoDBAnnotationDriver->isTransient($className);
    }    
}
