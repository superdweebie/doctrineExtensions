<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\AccessControl;

use SdsDoctrineExtensions\AbstractExtension;
use SdsDoctrineExtensions\AccessControl\Subscriber\AccessControl as AccessControlSubscriber;

/**
 * Defines the resouces this extension provies
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    /**
     *
     * @param \SdsDoctrineExtensions\Readonly\ExtensionConfig $config
     */
    public function __construct(ExtensionConfig $config){
        $this->config = $config;

        $this->subscribers = array(new AccessControlSubscriber(
            $config->getActiveUser(),
            $config->getAnnotationReader(),
            $config->getAccessControlCreate(),
            $config->getAccessControlUpdate(),
            $config->getAccessControlDelete()
        ));

        if ($config->getAccessControlRead()){
            $this->filters = array('freeze' => 'SdsDoctrineExtensions\AccessControl\Filter\ReadAccessControl');
        }
        
        $reflection = new \ReflectionClass($config->getPermissionClass());
        $this->documents = array($reflection->getNamespaceName() => dirname($reflection->getFileName()));        
    }
}
