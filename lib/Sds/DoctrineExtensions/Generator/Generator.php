<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Generator;

use Sds\DoctrineExtensions\DocumentManagerAwareInterface;
use Sds\DoctrineExtensions\DocumentManagerAwareTrait;


/**
 * Generate file from mapping information.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Generator implements DocumentManagerAwareInterface
{
    use DocumentManagerAwareTrait;

    protected $cacheSalt = 'Sds\Generator_';

    protected $resourceMap;

    public function getResourceMap() {
        return $this->resourceMap;
    }

    public function setResourceMap(ResourceMap $resourceMap) {
        $this->resourceMap = $resourceMap;
    }

    public function canGenerate($resourceName){
        return $this->getResourceMap()->has($resourceName);
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
}
