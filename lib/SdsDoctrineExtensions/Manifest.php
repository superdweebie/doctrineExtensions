<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions;

/**
 * Pass this class a configuration array with extension namespaces, and then retrieve the
 * required annotations, filters, subscribers, and document locations
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Manifest implements ExtensionInterface {

    /**
     * {@inheritdoc}
     */
    protected $config;

    /**
     *
     * @var array
     */
    protected $extensions = array();

    /**
     *
     * @param \SdsDoctrineExtensions\ManifestConfig $config
     * @throws \Exception
     */
    public function __construct(ManifestConfig $config) {
        $this->config = $config;
        foreach ($config->getExtensionConfigs() as $namespace => $extensionConfig){

            $extensionConfigClass = $namespace. '\ExtensionConfig';

            // Create specific config class if not given
            if (!$extensionConfig instanceof $extensionConfigClass) {
                $extensionConfig = new $extensionConfigClass;
            }

            // Inject annotation reader if required, but not given
            if ($extensionConfig instanceof AnnotationReaderConfigInterface &&
                $extensionConfig->getAnnoationReader() == null) {
                $extensionConfig->setAnnoationReader($config->getAnnoationReader());
            }

            // Create extension
            $extensionClass = $namespace . '\Extension';
            $extension = new $extensionClass($extensionConfig);
            if (!$extension instanceof ExtensionInterface) {
                throw new \Exception(sprintf('%s must be an instance of ExtensionInterface, but is not', $class));
            }
            $this->extensions[$namespace] = $extension;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig(){
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function getAnnotations(){
        $annotations = array();
        foreach ($this->extensions as $extension) {
            $annotations = array_merge($annotations, $extension->getAnnotations());
        }
        return $annotations;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(){
        $filters = array();
        foreach ($this->extensions as $extension) {
            $filters = array_merge($filters, $extension->getFilters());
        }
        return $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribers(){
        $subscribers = array();
        foreach ($this->extensions as $extension) {
            $subscribers = array_merge($subscribers, $extension->getSubscribers());
        }
        return $subscribers;
    }

    /**
     * {@inheritdoc}
     */
    public function getDocuments(){
        $documents = array();
        foreach ($this->extensions as $extension) {
            $documents = array_merge($documents, $extension->getDocuments());
        }
        return $documents;
    }
}