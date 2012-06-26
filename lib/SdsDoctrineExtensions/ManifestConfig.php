<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationReader;
use SdsCommon\User\ActiveUserAwareInterface;
use SdsCommon\User\ActiveUserAwareTrait;
use SdsCommon\User\UserInterface;

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
    public function __construct(
        Reader $annoationReader = null,
        array $extensionConfigs = array(),
        UserInterface $activeUser = null
        ){
        if (isset($annoationReader)) {
            $this->setAnnotationReader($annoationReader);
        } else {
            $this->setAnnotationReader(new AnnotationReader());
        }
        $this->setExtensionConfigs($extensionConfigs);
        isset($activeUser) ? $this->setActiveUser($activeUser) : null;
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