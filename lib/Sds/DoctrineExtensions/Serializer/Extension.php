<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer;

use Sds\DoctrineExtensions\AbstractExtension;
use Zend\StdLib\ArrayUtils;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    protected $subscribers = [
        'Sds\DoctrineExtensions\Serializer\AnnotationSubscriber'
    ];

    protected $defaultServiceManagerConfig = [
        'factories' => [
            'serializer' => 'Sds\DoctrineExtensions\Serializer\SerializerFactory'
        ]
    ];

    /** @var array */
    protected $dependencies = [
        'Sds\DoctrineExtensions\Annotation' => true
    ];

    protected $defaultReferenceSerializerServiceConfig = [
        'invokables' => [
            'refLazyReferenceSerializer' => 'Sds\DoctrineExtensions\Serializer\Reference\RefLazy',
            'simpleLazyReferenceSerializer' => 'Sds\DoctrineExtensions\Serializer\Reference\SimpleLazy',
            'eagerReferenceSerializer' => 'Sds\DoctrineExtensions\Serializer\Reference\Eager',
        ]
    ];

    protected $referenceSerializerServiceConfig = [];

    protected $defaultTypeSerializerServiceConfig = [
        'invokables' => [
            'dateToISO8601Serializer' => 'Sds\DoctrineExtensions\Serializer\Type\DateToISO8601',
            'dateToTimestamp' => 'Sds\DoctrineExtensions\Serializer\Type\DateToTimestamp'
        ],
    ];

    protected $typeSerializerServiceConfig = [];

    /** @var array */
    protected $typeSerializers = [
        'date' => 'dateToISO8601Serializer'
    ];

    /** @var int */
    protected $maxNestingDepth = 1;

    protected $classNameField = '_className';

    public function getReferenceSerializerServiceConfig() {
        return ArrayUtils::merge($this->defaultReferenceSerializerServiceConfig, $this->referenceSerializerServiceConfig);
    }

    public function setReferenceSerializerServiceConfig(array $referenceSerializerServiceConfig) {
        $this->referenceSerializerServiceConfig = $referenceSerializerServiceConfig;
    }

    public function getTypeSerializerServiceConfig() {
        return ArrayUtils::merge($this->defaultTypeSerializerServiceConfig, $this->typeSerializerServiceConfig);
    }

    public function setTypeSerializerServiceConfig(array $typeSerializerServiceConfig) {
        $this->typeSerializerServiceConfig = $typeSerializerServiceConfig;
    }

    public function getTypeSerializers() {
        return $this->typeSerializers;
    }

    public function setTypeSerializers(array $typeSerializers) {
        $this->typeSerializers = $typeSerializers;
    }

    public function getMaxNestingDepth() {
        return $this->maxNestingDepth;
    }

    public function setMaxNestingDepth($maxNestingDepth) {
        $this->maxNestingDepth = (integer) $maxNestingDepth;
    }

    public function getClassNameField() {
        return $this->classNameField;
    }

    public function setClassNameField($classNameField) {
        $this->classNameField = (string) $classNameField;
    }
}
