<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Dojo;

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

    protected $destPaths;

    /**
     *
     * @return string
     */
    public function getDestPaths() {
        return $this->destPaths;
    }

    /**
     *
     * @param array $destPath
     */
    public function setDestPaths(array $destPaths) {
        $this->destPaths = $destPaths;
    }

    /**
     *
     * @var array
     */
    protected $dependencies = array(
        'Sds\DoctrineExtensions\Serializer' => null,
        'Sds\DoctrineExtensions\Validator' => null
    );
}