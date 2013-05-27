<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Rest;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class EndpointMap
{
    protected $map = [];

    public function getMap() {
        return $this->map;
    }

    public function setMap($map) {
        $this->map = $map;
    }

    public function has($endpoint){
        return array_key_exists($endpoint, $this->map);
    }

    public function getClass($endpoint){
        if (isset($this->map[$endpoint])){
            if (is_string($this->map[$endpoint])){
                return $this->map[$endpoint];
            }
            if (is_array($this->map[$endpoint])){
                return $this->map[$endpoint]['class'];
            }
        }
        return false;
    }

    public function getCacheOptions($endpoint = null, $class = null){

        if (isset($class)){
            $endpoints = array_keys(array_filter($this->map, function($value) use ($class){
                if (is_string($value) && ($value == $class)){
                    return true;
                }
                if (isset($value['class']) && $value['class'] == $class){
                    return true;
                }
            }));
            if (count($endpoints) > 0){
                $endpoint = $endpoints[0];
            }
        }
        if (isset($endpoint) && isset($this->map[$endpoint]) && isset($this->map[$endpoint]['cache'])){
            $options = $this->map[$endpoint]['cache'];
        } else {
            $options = [];
        }
        return new CacheOptions($options);
    }

    public function getEndpoints($class){
        return array_keys(
            array_filter($this->map, function($value) use ($class){
                if (is_string($value) && ($value == $class)){
                    return true;
                }
                if (isset($value['class']) && $value['class'] == $class){
                    return true;
                }
            })
        );
    }
}
