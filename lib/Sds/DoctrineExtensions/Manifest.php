<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;

/**
 * Pass this class a configuration array with extension namespaces, and then retrieve the
 * required filters, subscribers, and document locations
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Manifest extends AbstractExtension {

    protected $defaultServiceManagerConfig = [
        'invokables' => [
            'documentManagerDelegatorFactory' => 'Sds\DoctrineExtensions\DocumentManagerDelegatorFactory'
        ],
        'factories' => [
            'extension.accessControl' => 'Sds\DoctrineExtensions\AccessControl\ExtensionFactory',
            'extension.annotation' => 'Sds\DoctrineExtensions\Annotation\ExtensionFactory',
            'extension.crypt' => 'Sds\DoctrineExtensions\Crypt\ExtensionFactory',
            'extension.dojo' => 'Sds\DoctrineExtensions\Dojo\ExtensionFactory',
            'extension.freeze' => 'Sds\DoctrineExtensions\Freeze\ExtensionFactory',
            'extension.generator' => 'Sds\DoctrineExtensions\Generator\ExtensionFactory',
            'extension.identity' => 'Sds\DoctrineExtensions\Identity\ExtensionFactory',
            'extension.owner' => 'Sds\DoctrineExtensions\Owner\ExtensionFactory',
            'extension.readonly' => 'Sds\DoctrineExtensions\Readonly\ExtensionFactory',
            'extension.reference' => 'Sds\DoctrineExtensions\Reference\ExtensionFactory',
            'extension.rest' => 'Sds\DoctrineExtensions\Rest\ExtensionFactory',
            'extension.serializer' => 'Sds\DoctrineExtensions\Serializer\ExtensionFactory',
            'extension.softdelete' => 'Sds\DoctrineExtensions\SoftDelete\ExtensionFactory',
            'extension.stamp' => 'Sds\DoctrineExtensions\Stamp\ExtensionFactory',
            'extension.state' => 'Sds\DoctrineExtensions\State\ExtensionFactory',
            'extension.validator' => 'Sds\DoctrineExtensions\Validator\ExtensionFactory',
            'extension.zone' => 'Sds\DoctrineExtensions\Zone\ExtensionFactory',
            'subscriber.lazySubscriber' => 'Sds\DoctrineExtensions\LazySubscriberFactory',
        ],
    ];

    /**
     * Keys are extension namespaces
     * Values are extensionConfig objects
     *
     * @var array
     */
    protected $extensionConfigs = [];

    protected $lazySubscriberConfig;

    protected $documentManager;

    protected $serviceManager;

    protected $initalized = false;

    public function getExtensionConfigs() {
        return $this->extensionConfigs;
    }

    public function setExtensionConfigs(array $extensionConfigs) {
        $this->extensionConfigs = $extensionConfigs;
    }

    public function getLazySubscriberConfig() {
        return $this->lazySubscriberConfig;
    }

    public function setLazySubscriberConfig($lazySubscriberConfig) {
        $this->lazySubscriberConfig = $lazySubscriberConfig;
    }

    public function getDocumentManager() {
        return $this->documentManager;
    }

    public function setDocumentManager($documentManager) {
        $this->documentManager = $documentManager;
    }

    public function setServiceManager($serviceManager) {
        $this->serviceManager = $serviceManager;
    }

    public function getServiceManager(){
        $this->initalize();
        return $this->serviceManager;
    }

    public function getInitalized() {
        return $this->initalized;
    }

    public function setInitalized($initalized) {
        $this->initalized = $initalized;
    }

    protected function initalize() {
        if ($this->initalized){
            return;
        }
        $this->initalized = true;

        if (isset($this->serviceManager)){
            $serviceManager = $this->serviceManager;
        } else {
            $this->defaultServiceManagerConfig['delegators'][$this->documentManager] = ['documentManagerDelegatorFactory'];
            $serviceManager = self::createServiceManager($this->defaultServiceManagerConfig);
            $this->serviceManager = $serviceManager;
        }
        $serviceManager->setService('manifest', $this);

        foreach ($this->extensionConfigs as $name => $extensionConfig){
            $this->expandExtensionConfig($name);
        }

        //merge all the configs
        $config = [
            'service_manager_config' => [],
            'filters' => [],
            'documents' => [],
            'cli_commands' => [],
            'cli_helpers' => []
        ];
        foreach ($this->extensionConfigs as $extensionConfig){
            $config = ArrayUtils::merge(
                $config,
                array_intersect_key(
                    $extensionConfig,
                    $config
                )
            );
        }
        $this->serviceManagerConfig = ArrayUtils::merge($config['service_manager_config'], $this->serviceManagerConfig);
        $this->filters = ArrayUtils::merge($config['filters'], $this->filters);
        $this->documents = ArrayUtils::merge($config['documents'], $this->documents);
        $this->cliCommands = ArrayUtils::merge($config['cli_commands'], $this->cliCommands);
        $this->cliHelpers = ArrayUtils::merge($config['cli_helpers'], $this->cliHelpers);

        //Apply service manager config
        $serviceManagerConfig = new Config($this->serviceManagerConfig);
        $serviceManagerConfig->configureServiceManager($serviceManager);

        //Make sure default service manager config is included in the main service manager config variable
        $this->serviceManagerConfig = ArrayUtils::merge($this->defaultServiceManagerConfig, $this->serviceManagerConfig);

        //create lazySubscriber configuration
        $lazySubscriberConfig = [];
        foreach ($this->extensionConfigs as $extensionConfig){
            foreach ($extensionConfig['subscribers'] as $subscriber){
                foreach ($serviceManager->get($subscriber)->getSubscribedEvents() as $event){
                    if (!isset($lazySubscriberConfig[$event])){
                        $lazySubscriberConfig[$event] = [];
                    }
                    $lazySubscriberConfig[$event][] = $subscriber;
                }
            }
            foreach ($this->subscribers as $subscriber){
                foreach ($serviceManager->get($subscriber)->getSubscribedEvents() as $event){
                    if (!isset($lazySubscriberConfig[$event])){
                        $lazySubscriberConfig[$event] = [];
                    }
                    $lazySubscriberConfig[$event][] = $subscriber;
                }
            }
        }
        $this->lazySubscriberConfig = $lazySubscriberConfig;
        $this->subscribers = ['subscriber.lazySubscriber'];
    }

    protected function expandExtensionConfig($name){

        //Get extension
        $extension = $this->serviceManager->get($name);

        //ensure dependencies get expaned also
        foreach ($extension->getDependencies() as $dependencyName => $dependencyConfig){
            if ( ! isset($this->extensionConfigs[$dependencyName]) || is_bool($this->extensionConfigs[$dependencyName])){
                $this->expandExtensionConfig($dependencyName);
            }
        }

        $this->extensionConfigs[$name] = $extension->toArray();
    }

    public static function createServiceManager($config = []){

        $serviceManager = new ServiceManager(new Config($config));

        $serviceManager->addInitializer(function($instance, ServiceLocatorInterface $serviceLocator){
            if ($instance instanceof DocumentManagerAwareInterface) {
                $instance->setDocumentManager($serviceLocator->get($serviceLocator->get('manifest')->getDocumentManager()));
            }
        });
        $serviceManager->addInitializer(function($instance, ServiceLocatorInterface $serviceLocator){
            if ($instance instanceof ServiceLocatorAwareInterface) {
                $instance->setServiceLocator($serviceLocator);
            }
        });

        return $serviceManager;
    }

    /**
     * Cast to array
     *
     * @return array
     */
    public function toArray()
    {
        $this->initalize();
        $array = parent::toArray();
        unset($array['default_service_manager_config']);
        unset($array['service_manager']);
        return $array;
    }
}