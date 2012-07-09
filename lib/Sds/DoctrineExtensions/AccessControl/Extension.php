<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

use Sds\DoctrineExtensions\AbstractExtension;
use Sds\DoctrineExtensions\AccessControl\Subscriber\AccessControl as AccessControlSubscriber;

/**
 * Defines the resouces this extension provies
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    /**
     *
     * @param array|\Traversable|\Sds\DoctrineExtensions\AccessControl\ExtensionConfig $config
     */
    public function __construct($config){
        
        $this->configClass = __NAMESPACE__ . '\ExtensionConfig';
        parent::__construct($config);
        $config = $this->getConfig();

        $this->subscribers = array(new AccessControlSubscriber(
            $config->getAnnotationReader(),
            $config->getActiveUser(),
            $config->getAccessControlCreate(),
            $config->getAccessControlUpdate(),
            $config->getAccessControlDelete()
        ));

        if ($config->getAccessControlRead()){
            $this->filters = array('readAccessControl' => 'Sds\DoctrineExtensions\AccessControl\Filter\ReadAccessControl');
        }

        $reflection = new \ReflectionClass($config->getPermissionClass());
        $this->documents = array($reflection->getNamespaceName() => dirname($reflection->getFileName()));
    }
}
