<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Doctrine\ODM\MongoDB\DocumentManager;
use Zend\Stdlib\ArrayUtils;

/**
 * Pass this class a configuration array with extension namespaces, and then retrieve the
 * required filters, subscribers, and document locations
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Manifest extends AbstractExtension {

    /**
     * Keys are extension namespaces
     * Values are extensionConfig objects
     *
     * @var array
     */
    protected $extensionConfigs;

    protected $extensions;

    protected $defaultServiceManagerConfig = [
        'abstract_factories' => [
            'Sds\DoctrineExtensions\AbstractExtensionFactory'
        ],
        'initializers' => [
            'Sds\DoctrineExtensions\ServiceLocatorInitalizer',
            'Sds\DoctrineExtensions\DocumentManagerInitalizer'
        ]
    ];

    protected $serviceManager;

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

    public function getExtensionConfig($namespace) {
        if (isset($this->extensionConfigs[(string) $namespace])){
            $this->extensionConfigs[(string) $namespace];
        }
    }

    public static function staticBootstrapped(ServiceManager $serviceManager){
        $eventManager = $serviceManager->get('documentManager')->getEventManager();
        if ($eventManager->hasListeners(Events::onBootstrapped)) {
            $eventManager->dispatchEvent(Events::onBootstrapped, new BootstrappedEventArgs($serviceManager));
        }
    }

    public function bootstrapped(){
        $this->staticBootstrapped($this->getServiceManager());
        return $this;
    }

    public function setDocumentManagerService(DocumentManager $documentManager){
        $this->getServiceManager()->setService('documentManager', $documentManager);
        return $this;
    }

    protected function getExtensions(){
        if ( ! isset($this->extensions)){
            foreach ($this->extensionConfigs as $namespace => $extensionConfig){
                $this->addExtension($namespace, $extensionConfig);
            }
        }
        return $this->extensions;
    }

    /**
     *
     * @param string $namespace
     * @param array | \Sds\DoctrineExtensions\AbstractConfig $extensionConfig
     * @throws \Exception
     */
    protected function addExtension($namespace, $extensionConfig){

        //Check if the extension is already added
        if (isset($this->extensions[$namespace]) || ! (boolean) $extensionConfig){
            return;
        }

        if (is_bool($extensionConfig)){
            $extensionConfig = [];
        }

        $extensionClass = $namespace. '\Extension';
        $extension = new $extensionClass($extensionConfig);

        $this->extensions[$namespace] = $extension;

        //Add dependencies
        $manifestConfigs = $this->extensionConfigs;
        foreach ($extension->getDependencies() as $namespace => $dependencyConfig){
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
    public function getFilters(){
        $filters = [];
        foreach ($this->getExtensions() as $extension) {
            $filters = array_merge($filters, $extension->getFilters());
        }
        return $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribers(){
        $subscribers = [];
        foreach ($this->getExtensions() as $extension) {
            foreach ($extension->getSubscribers() as $subscriber){
                if (is_string($subscriber)){
                    if (!isset($masterLazySubscriber)){
                        $masterLazySubscriber = new MasterLazySubscriber;
                        $subscribers[] = $masterLazySubscriber;
                    }
                    $masterLazySubscriber->addLazySubscriber($subscriber);
                } else {
                    $subscribers[] = $subscriber;
                }
            }
        }
        return $subscribers;
    }

    /**
     * {@inheritdoc}
     */
    public function getDocuments(){
        $documents = [];
        foreach ($this->getExtensions() as $extension) {
            $documents = array_merge($documents, $extension->getDocuments());
        }
        return $documents;
    }

    /**
     * {@inheritdoc}
     */
    public function getCliCommands(){
        $cliCommands = [];
        foreach ($this->getExtensions() as $extension) {
            $cliCommands = array_merge($cliCommands, $extension->getCliCommands());
        }
        return $cliCommands;
    }

    /**
     * {@inheritdoc}
     */
    public function getCliHelpers(){
        $cliHelpers = [];
        foreach ($this->getExtensions() as $extension) {
            $cliHelpers = array_merge($cliHelpers, $extension->getcliHelpers());
        }
        return $cliHelpers;
    }

    public function getServiceManagerConfig(){
        $config = $this->defaultServiceManagerConfig;
        foreach ($this->getExtensions() as $extension){
            $config = ArrayUtils::merge($config, $extension->getServiceManagerConfig());
        }
        return ArrayUtils::merge($config, $this->serviceManagerConfig);
    }

    public function getServiceManager(){
        if (!isset($this->serviceManager)){
            $this->serviceManager = ServiceManagerFactory::create(
                $this->getServiceManagerConfig(),
                $this->extensionConfigs
            );
        }
        return $this->serviceManager;
    }
}