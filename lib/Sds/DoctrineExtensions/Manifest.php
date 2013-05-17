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
            'extension.accessControl' => 'Sds\DoctrineExtensions\AccessControl\Extension',
            'extension.annotation' => 'Sds\DoctrineExtensions\Annotation\Extension',
            'extension.crypt' => 'Sds\DoctrineExtensions\Crypt\Extension',
            'extension.dojo' => 'Sds\DoctrineExtensions\Dojo\Extension',
            'extension.freeze' => 'Sds\DoctrineExtensions\Freeze\Extension',
            'extension.generator' => 'Sds\DoctrineExtensions\Generator\Extension',
            'extension.identity' => 'Sds\DoctrineExtensions\Identity\Extension',
            'extension.owner' => 'Sds\DoctrineExtensions\Owner\Extension',
            'extension.readonly' => 'Sds\DoctrineExtensions\Readonly\Extension',
            'extension.reference' => 'Sds\DoctrineExtensions\Reference\Extension',
            'extension.rest' => 'Sds\DoctrineExtensions\Rest\Extension',
            'extension.serializer' => 'Sds\DoctrineExtensions\Serializer\Extension',
            'extension.softdelete' => 'Sds\DoctrineExtensions\SoftDelete\Extension',
            'extension.stamp' => 'Sds\DoctrineExtensions\Stamp\Extension',
            'extension.state' => 'Sds\DoctrineExtensions\State\Extension',
            'extension.validator' => 'Sds\DoctrineExtensions\Validator\Extension',
            'extension.zone' => 'Sds\DoctrineExtensions\Zone\Extension',
            'documentManagerDelegatorFactory' => 'Sds\DoctrineExtensions\DocumentManagerDelegatorFactory'
        ],
        'factories' => [
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
        $this->initalize();
        return $this->extensionConfigs;
    }

    public function setExtensionConfigs(array $extensionConfigs) {
        $this->extensionConfigs = $extensionConfigs;
    }

    public function getLazySubscriberConfig() {
        $this->initalize();
        return $this->lazySubscriberConfig;
    }

    public function setLazySubscriberConfig($lazySubscriberConfig) {
        $this->lazySubscriberConfig = $lazySubscriberConfig;
    }

    public function getDocumentManager() {
        $this->initalize();
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

        foreach ($this->extensionConfigs as $name => $extensionConfig){
            $this->expandExtensionConfig($name, $extensionConfig);
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

        $serviceManager->setService('manifest', $this->toArray());
    }

    protected function expandExtensionConfig($name, $extensionConfig){

        if (is_bool($extensionConfig) && $extensionConfig){
            $extensionConfig = [];
        }

        //Get extension
        $extension = $this->serviceManager->get($name);
        $extension->setFromArray($extensionConfig);

        //ensure dependencies get expaned also
        foreach ($extension->getDependencies() as $dependencyName => $dependencyConfig){
            if ( ! isset($this->extensionConfigs[$dependencyName]) || is_bool($this->extensionConfigs[$dependencyName])){
                $this->expandExtensionConfig($dependencyName, $dependencyConfig);
            }
        }

        $this->extensionConfigs[$name] = $extension->toArray();
    }

    public static function createServiceManager($config = []){

        $serviceManager = new ServiceManager(new Config($config));

        $serviceManager->addInitializer(function($instance, ServiceLocatorInterface $serviceLocator){
            if ($instance instanceof DocumentManagerAwareInterface) {
                $instance->setDocumentManager($serviceLocator->get($serviceLocator->get('manifest')['document_manager']));
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
        return parent::toArray();
    }
}