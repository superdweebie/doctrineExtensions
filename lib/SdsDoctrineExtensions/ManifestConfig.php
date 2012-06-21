<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions;

use Doctrine\Common\Annotations\Reader;

class ManifestConfig extends AbstractExtensionConfig implements AnnotationReaderConfigInterface {

    use AnnotationReaderConfigTrait;

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
    public function __construct(Reader $annoationReader = null, array $extensionConfigs = array()){
        $this->setAnnoationReader($annoationReader);
        $this->setExtensionConfigs($extensionConfigs);
    }

    /**
     *
     * @return array
     */
    public function getExtensionConfigs() {
        return $this->extensionConfigs;
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
     * @param \SdsDoctrineExtensions\AbstractConfig $config
     */
    public function addExtensionConfig($namespace, AbstractExtensionConfig $config) {
        $this->extensionConfigs[(string) $namespace] = $config;
    }
}