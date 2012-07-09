<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;
use Sds\Common\User\ActiveUserAwareInterface;
use Sds\Common\User\ActiveUserAwareTrait;
use Sds\Common\User\UserInterface;

class ManifestConfig extends AbstractExtensionConfig
implements
    AnnotationReaderAwareInterface,
    ActiveUserAwareInterface
{

    use AnnotationReaderAwareTrait;
    use ActiveUserAwareTrait;

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
     * @param \Sds\DoctrineExtensions\AbstractConfig $config
     */
    public function addExtensionConfig($namespace, AbstractExtensionConfig $config) {
        $this->extensionConfigs[(string) $namespace] = $config;
    }
}