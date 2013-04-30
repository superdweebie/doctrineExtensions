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
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ResourceMap implements DocumentManagerAwareInterface
{

    use DocumentManagerAwareTrait;

    protected $cacheId = 'Sds\DoctrineExtensions\Generate\ResourceMap';

    public function getCacheId() {
        return $this->cacheId;
    }

    public function setCacheId($cacheId) {
        $this->cacheId = $cacheId;
    }

    public function has($resource){
        return array_key_exists($resource, $this->getMap());
    }

    public function get($resource){
        return $this->getMap()[$resource];
    }

    public function getMap(){

        $cacheDriver = $this->documentManager->getConfiguration()->getMetadataCacheImpl();

        if (! $this->map = $cacheDriver->fetch($this->cacheId)){
            $this->map = [];
            foreach($this->documentManager->getMetadataFactory()->getAllMetadata() as $metadata){
                if (isset($metadata->generator)){
                    foreach($metadata->generator as $resource => $config){
                        $config['className'] = $metadata->name;
                        $this->map[$resource] = $config;
                    }
                }
            }
            $cacheDriver->save($this->cacheId, $this->map);
        }

        return $this->map;
    }
}
