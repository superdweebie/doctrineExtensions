<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

use Sds\Common\AccessControl\AccessControlIdentityInterface;
use Sds\DoctrineExtensions\AbstractExtension;

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

        $this->subscribers = array(new Subscriber(
            $config->getAnnotationReader(),
            $config->getRoles()
        ));

        $this->filters = array('readAccessControl' => 'Sds\DoctrineExtensions\AccessControl\Filter\ReadAccessControl');

        $reflection = new \ReflectionClass($config->getPermissionClass());
        $this->documents = array($reflection->getNamespaceName() => dirname($reflection->getFileName()));
    }
}
