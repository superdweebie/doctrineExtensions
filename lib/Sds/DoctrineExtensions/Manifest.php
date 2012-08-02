<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Sds\Common\User\ActiveUserAwareInterface;
use Sds\DoctrineExtensions\Exception;

/**
 * Pass this class a configuration array with extension namespaces, and then retrieve the
 * required annotations, filters, subscribers, and document locations
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Manifest extends AbstractExtension {

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
     * @param \Sds\DoctrineExtensions\ManifestConfig $config
     */
    public function __construct(ManifestConfig $config) {
        $this->config = $config;

        foreach ($config->getExtensionConfigs() as $namespace => $extensionConfig){
            $this->addExtension($namespace, $extensionConfig);
        }
    }

    /**
     *
     * @param string $namespace
     * @param array | \Sds\DoctrineExtensions\AbstractConfig $extensionConfig
     * @throws \Exception
     */
    public function addExtension($namespace, $extensionConfig){

        //Check if the extension is already added
        if (isset($this->extensions[$namespace])){
            return;
        }

        $config = $this->config;

        $extensionConfigClass = $namespace. '\ExtensionConfig';

        // Create specific config class if not given
        if (!$extensionConfig instanceof $extensionConfigClass) {
            $extensionConfig = new $extensionConfigClass($extensionConfig);
        }

        // Inject annotation reader if required, but not given
        if ($extensionConfig instanceof AnnotationReaderAwareInterface &&
            $extensionConfig->getAnnotationReader() == null) {
            $extensionConfig->setAnnotationReader($config->getAnnotationReader());
        }

        //Inject active user if required, but not given
        if ($extensionConfig instanceof ActiveUserAwareInterface &&
            $extensionConfig->getActiveUser() == null) {
            $extensionConfig->setActiveUser($config->getActiveUser());
        }

        $config->setExtensionConfig($namespace, $extensionConfig);

        // Create extension
        $extensionClass = $namespace . '\Extension';
        $extension = new $extensionClass($extensionConfig);
        if (!$extension instanceof ExtensionInterface) {
            throw new Exception\UnexpectedValueException(sprintf(
                '%s must be an instance of ExtensionInterface, but is not',
                $extensionClass
            ));
        }
        $this->extensions[$namespace] = $extension;

        //Add dependencies
        $manifestConfigs = $config->getExtensionConfigs();
        foreach ($extensionConfig->getDependencies() as $namespace => $dependencyConfig){
            //Check for manifest config, and use that instead if present
            if (isset($manifestConfigs[$namespace])){
                $dependencyConfig = $manifestConfigs[$namespace];
            }
            $this->addExtension($namespace, $dependencyConfig);
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

    /**
     * {@inheritdoc}
     */
    public function getCliCommands(){
        $cliCommands = array();
        foreach ($this->extensions as $extension) {
            $cliCommands = array_merge($cliCommands, $extension->getCliCommands());
        }
        return $cliCommands;
    }

    /**
     * {@inheritdoc}
     */
    public function getCliHelpers(){
        $cliHelpers = array();
        foreach ($this->extensions as $extension) {
            $cliHelpers = array_merge($cliHelpers, $extension->getcliHelpers());
        }
        return $cliHelpers;
    }
}