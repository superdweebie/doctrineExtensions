<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Doctrine\Common\EventSubscriber;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class MasterLazySubscriber implements EventSubscriber, ServiceLocatorAwareInterface {

    use ServiceLocatorAwareTrait;

    protected $subscribers = [];

    protected $events;

    public function getConfig(){
        $this->getSubscribedEvents();
        return ['subscribers' => $this->subscribers, 'events'=>$this->events];
    }

    public function setConfig($config){
        $this->subscribers = $config['subscribers'];
        $this->events = $config['events'];
    }

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        if (!isset($this->events)){
            $this->events[Events::onBootstrapped] = [];
            foreach ($this->subscribers as $name => $subscriber){
                foreach ($subscriber::getStaticSubscribedEvents() as $event){
                    if (!isset($this->events[$event])){
                        $this->events[$event] = [];
                    }
                    $this->events[$event][] = $name;
                }
            }
        }
        return array_keys($this->events);
    }

    public function addLazySubscriber($name){
        $this->subscribers[$name] = $name;
    }

    public function onBootstrapped(BootstrappedEventArgs $eventArgs)
    {
        $this->serviceLocator = $eventArgs->getServiceLocator();
        $this->__call(Events::onBootstrapped, [$eventArgs]);
    }

    public function __call($event, $arguments) {
        foreach ($this->events[$event] as $name){
            if (is_string($this->subscribers[$name])){
                if ($this->serviceLocator->has($name)){
                    $this->subscribers[$name] = $this->serviceLocator->get($name);
                } else {
                    $subscriber = new $name;
                    $this->serviceLocator->initializeInstance($subscriber);
                    $this->subscribers[$name] = $subscriber;
                }
            }
            call_user_func_array([$this->subscribers[$name], $event], $arguments);
        }
    }
}