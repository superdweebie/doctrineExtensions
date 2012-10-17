<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Doctrine\Common\Annotations\AnnotationReader;

class ManifestConfig extends AbstractExtensionConfig
{


    /**
     * Keys are extension namespaces
     * Values are extensionConfig objects
     *
     * @var array
     */
    protected $extensionConfigs;

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annoationReader
     * @param array $extensionConfigs
     */
    public function __construct($options = null){
        parent::__construct($options);
        if (!isset($this->annotationReader)){
            $this->setAnnotationReader(new AnnotationReader());
        }
    }

    /**
     *
     * @return array
     */
    public function getExtensionConfigs() {
        return $this->extensionConfigs;
    }

    public function getExtensionConfig($namespace) {
        $a = $this->extensionConfigs[(string) $namespace];
        return isset($this->extensionConfigs[(string) $namespace]) ? $this->extensionConfigs[(string) $namespace] : null;
    }

    /**
     *
     * @param array $extensionConfigs
     */
    public function setExtensionConfigs(array $extensionConfigs) {
        $this->extensionConfigs = $extensionConfigs;
    }

    /**
     *
     * @param string $namespace
     * @param array | \Sds\DoctrineExtensions\AbstractConfig $config
     */
    public function setExtensionConfig($namespace, $config) {
        $this->extensionConfigs[(string) $namespace] = $config;
    }
}