<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Rest;

use Sds\DoctrineExtensions\DocumentManagerAwareInterface;
use Sds\DoctrineExtensions\DocumentManagerAwareTrait;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class EndpointMap implements DocumentManagerAwareInterface
{

    use DocumentManagerAwareTrait;

    protected $cacheId = 'Sds\DoctrineExtensions\Rest\EndpointMap';

    protected $map = null;

    public function getCacheId() {
        return $this->cacheId;
    }

    public function setCacheId($cacheId) {
        $this->cacheId = $cacheId;
    }

    public function has($endpoint){
        return array_key_exists($endpoint, $this->getMap());
    }

    public function get($endpoint){
        return $this->getMap()[$endpoint];
    }

    public function getMap(){

        if (isset($this->map)){
            return $this->map;
        }

        $cacheDriver = $this->documentManager->getConfiguration()->getMetadataCacheImpl();

        if ($cacheDriver->contains($this->cacheId)){
            $this->map = $cacheDriver->fetch($this->cacheId);
        } else {
            $this->map = [];
            foreach($this->documentManager->getMetadataFactory()->getAllMetadata() as $metadata){
                if (isset($metadata->rest)){
                    $this->map[$metadata->rest['endpoint']] = ['className' => $metadata->name];
                }
            }
            $cacheDriver->save($this->cacheId, $this->map);
        }

        return $this->map;
    }
}
