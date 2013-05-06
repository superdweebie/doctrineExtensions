<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Generator;

use Sds\DoctrineExtensions\DocumentManagerAwareInterface;
use Sds\DoctrineExtensions\DocumentManagerAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Generate file from mapping information.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Generator implements ServiceLocatorAwareInterface, DocumentManagerAwareInterface
{
    use ServiceLocatorAwareTrait;
    use DocumentManagerAwareTrait;

    protected $cacheSalt = 'Sds\Generator_';

    protected $resourceMap;

    public function canGenerate($resource){
        return $this->getResourceMap()->has($resource);
    }

    public function generate($resourceName){

        $cacheDriver = $this->documentManager->getConfiguration()->getMetadataCacheImpl();

        $id = $this->cacheSalt . $resourceName;
        if ($resource = $cacheDriver->fetch($id)){
            return $resource;
        }

        $config = $this->resourceMap->get($resourceName);
        $event = $config['event'];
        $resource =  new \stdClass();

        $eventManager = $this->documentManager->getEventManager();
        if ($eventManager->hasListeners($event)) {
            $eventManager->dispatchEvent(
                $event,
                new GenerateEventArgs(
                    $resourceName,
                    $config['className'],
                    $this->documentManager,
                    $config['options'],
                    $resource
               )
            );
        }

        $cacheDriver->save($id, $resource->content);

        return $resource->content;
    }

    public function getResourceMap(){
        if (!isset($this->resourceMap)){
            $this->resourceMap = $this->serviceLocator->get('resourceMap');
        }
        return $this->resourceMap;
    }
}
