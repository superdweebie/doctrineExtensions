<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class DocumentManagerInitalizer implements InitializerInterface {

    protected $documentManager;

    public function initialize($instance, ServiceLocatorInterface $serviceLocator){
        if ($instance instanceof DocumentManagerAwareInterface) {
            if (!isset($this->documentManager)){
                $this->documentManager = $serviceLocator->get('documentManager');
            }
            $instance->setDocumentManager($this->documentManager);
        }
    }
}