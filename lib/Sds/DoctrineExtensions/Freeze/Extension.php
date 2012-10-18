<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze;

use Sds\DoctrineExtensions\AbstractExtension;
use Sds\DoctrineExtensions\Freeze\AccessControl\FreezeSubscriber;
use Sds\DoctrineExtensions\Freeze\AccessControl\ThawSubscriber;

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

        if ($config->getEnableFreezeStamps()) {
            $this->subscribers[] = new StampSubscriber($config->getIdentityName());
        }

        if ($config->getEnableAccessControl()){
            $this->subscribers[] = new FreezeSubscriber($config->getRoles());
            $this->subscribers[] = new ThawSubscriber($config->getRoles());
        }
        $this->filters = array('freeze' => 'Sds\DoctrineExtensions\Freeze\Filter\Freeze');
    }

    public function setIdentity($identity){
        parent::setIdentity($identity);
        foreach ($this->subscribers as $subscriber){
            switch (true){
                case $subscriber instanceof StampSubscriber:
                    $subscriber->setIdentityName($identity->getIdentityName());
                    break;
                case $subscriber instanceof AccessControl\FreezeSubscriber:
                    $subscriber->setRoles($identity->getRoles());
                    break;
                case $subscriber instanceof AccessControl\ThawSubscriber:
                    $subscriber->setRoles($identity->getRoles());
                    break;
            }
        }
    }
}
