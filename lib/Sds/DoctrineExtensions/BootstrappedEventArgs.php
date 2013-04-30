<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Doctrine\Common\EventArgs as BaseEventArgs;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class BootstrappedEventArgs extends BaseEventArgs {

    protected $serviceLocator;

    /**
     *
     * @param object $document
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager
     * @param array $messages
     */
    public function __construct(
        ServiceLocatorInterface $serviceLocator
    ) {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }
}