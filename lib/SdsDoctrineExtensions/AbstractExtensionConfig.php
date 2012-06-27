<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions;

/**
 * A base class which extensions configs must extend
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractExtensionConfig {

    /**
     * List of other extensions which must be loaded
     * for this extension to work
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     *
     * @return array
     */
    public function getDependencies() {
        return $this->dependencies;
    }

    /**
     *
     * @param array $dependencies
     */
    public function setDependencies(array $dependencies) {
        $this->dependencies = $dependencies;
    }

    /**
     *
     * @param string $namespace
     * @param \SdsDoctrineExtensions\AbstractExtensionConfig $config
     */
    public function addDependency($namespace, AbstractExtensionConfig $config = null){
        $this->dependencies[$namespace] = $config;
    }

    /**
     *
     * @param string $namespace
     */
    public function removeDependency($namespace){
        unset($this->dependencies[$namespace]);
    }
}
