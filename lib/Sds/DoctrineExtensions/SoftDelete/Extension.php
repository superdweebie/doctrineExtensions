<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\SoftDelete;

use Sds\DoctrineExtensions\AbstractExtension;
use Sds\DoctrineExtensions\SoftDelete\AccessControl;


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

        $this->subscribers = array(new Subscriber($config->getAnnotationReader()));
        if ($config->getEnableSoftDeleteStamps()) {
            $this->subscribers[] = new StampSubscriber($config->getIdentityName());
        }
        if ($config->getEnableAccessControl()){
            $this->subscribers[] = new AccessControl\SoftDeleteSubscriber($config->getRoles());
            $this->subscribers[] = new AccessControl\RestoreSubscriber($config->getRoles());
        }

        $this->filters = array('softDelete' => 'Sds\DoctrineExtensions\SoftDelete\Filter\SoftDelete');
    }

    public function setIdentity($identity){
        parent::setIdentity($identity);
        foreach ($this->subscribers as $subscriber){
            switch (true){
                case $subscriber instanceof StampSubscriber:
                    $subscriber->setIdentityName($identity->getIdentityName());
                    break;
                case $subscriber instanceof AccessControl\SoftDeleteSubscriber:
                    $subscriber->setRoles($identity->getRoles());
                    break;
                case $subscriber instanceof AccessControl\RestoreSubscriber:
                    $subscriber->setRoles($identity->getRoles());
                    break;
            }
        }
    }
}
