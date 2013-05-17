<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    protected $subscribers = [
        'subscriber.serializer.annotation'
    ];

    protected $serviceManagerConfig = [
        'invokables' => [
            'subscriber.serializer.annotation' => 'Sds\DoctrineExtensions\Serializer\AnnotationSubscriber',
            'serializer.reference.refLazy'     => 'Sds\DoctrineExtensions\Serializer\Reference\RefLazy',
            'serializer.reference.simpleLazy'  => 'Sds\DoctrineExtensions\Serializer\Reference\SimpleLazy',
            'serializer.reference.eager'       => 'Sds\DoctrineExtensions\Serializer\Reference\Eager',
            'serializer.type.dateToISO8601'    => 'Sds\DoctrineExtensions\Serializer\Type\DateToISO8601',
            'serializer.type.dateToTimestamp'  => 'Sds\DoctrineExtensions\Serializer\Type\DateToTimestamp'
        ],
        'factories' => [
            'serializer' => 'Sds\DoctrineExtensions\Serializer\SerializerFactory',
        ]
    ];

    /** @var array */
    protected $dependencies = [
        'extension.annotation' => true
    ];

    /** @var array */
    protected $typeSerializers = [
        'date' => 'serializer.type.dateToISO8601'
    ];

    /** @var int */
    protected $maxNestingDepth = 1;

    protected $classNameField = '_className';

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
