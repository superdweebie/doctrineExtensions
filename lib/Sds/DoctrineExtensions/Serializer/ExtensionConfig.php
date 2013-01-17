<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Serializer;

use Sds\DoctrineExtensions\AbstractExtensionConfig;
use Sds\DoctrineExtensions\ClassNamePropertyTrait;
/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig {

    use ClassNamePropertyTrait;

    protected $typeSerializers = [];
    
    /**
     *
     * @var array
     */
    protected $dependencies = array(
        'Sds\DoctrineExtensions\Annotation' => null,
        'Sds\DoctrineExtensions\Accessor' => null
    );

    public function getTypeSerializers() {
        return $this->typeSerializers;
    }

    public function setTypeSerializers($typeSerializers) {
        $this->typeSerializers = $typeSerializers;
    }
}
