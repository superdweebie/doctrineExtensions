<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Audit;

use Sds\Common\Identity\IdentityInterface;
use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension provies
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    public function __construct($config){

        $this->configClass = __NAMESPACE__ . '\ExtensionConfig';
        parent::__construct($config);
        $config = $this->getConfig();

        if ($config->getIdentityName() == null){
            $identity = $config->getIdentity();
            if ($identity instanceof IdentityInterface){
                $config->setIdentityName($identity->getName());
            }
        }

        $this->subscribers = array(new Subscriber(
            $config->getAnnotationReader(),
            $config->getIdentityName(),
            $config->getAuditClass()
        ));

        $reflection = new \ReflectionClass($config->getAuditClass());
        $this->documents = array($reflection->getNamespaceName() => dirname($reflection->getFileName()));
    }
}
