<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */

namespace Sds\DoctrineExtensions;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceManager as BaseServiceManager;

/**
 * Extends ServiceManager to expose initializers
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ServiceManager extends BaseServiceManager
{

    public function initializeInstance($instance){

        foreach ($this->initializers as $initializer) {
            if ($initializer instanceof InitializerInterface) {
                $initializer->initialize($instance, $this);
            } elseif (is_object($initializer) && is_callable($initializer)) {
                $initializer($instance, $this);
            } else {
                call_user_func($initializer, $instance, $this);
            }
        }
    }
}
