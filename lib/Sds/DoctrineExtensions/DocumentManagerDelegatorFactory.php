<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Sds\DoctrineExtensions\BootstrapEventArgs;
use Sds\DoctrineExtensions\Events;
use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class DocumentManagerDelegatorFactory implements DelegatorFactoryInterface
{

    protected $documentManagers = [];

    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {

        if (isset($this->documentManagers[$name])){
            return $this->documentManagers[$name];
        } else {
            $this->documentManagers[$name] = call_user_func($callback);
            $eventManager = $this->documentManagers[$name]->getEventManager();
            if ($eventManager->hasListeners(Events::onBootstrap)) {
                $eventManager->dispatchEvent(Events::onBootstrap, new BootstrapEventArgs);
            }
        }

        return $this->documentManagers[$name];
    }
}