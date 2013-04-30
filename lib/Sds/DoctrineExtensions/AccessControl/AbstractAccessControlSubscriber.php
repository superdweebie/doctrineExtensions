<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

use Sds\DoctrineExtensions\AbstractLazySubscriber;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractAccessControlSubscriber extends AbstractLazySubscriber implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    protected $accessController;

    protected $hasAccessController;

    protected function getAccessController(){
        if (!isset($this->hasAccessController)){
            $this->hasAccessController = $this->serviceLocator->has('accessController');
            if ($this->hasAccessController){
                $this->accessController = $this->serviceLocator->get('accessController');
            }
        }       
        return $this->accessController;
    }
}
