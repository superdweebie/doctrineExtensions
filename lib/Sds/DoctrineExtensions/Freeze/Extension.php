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

        $activeUser = $config->getActiveUser();

        $this->subscribers = array(new Subscriber($config->getAnnotationReader()));
        if ($config->getUseFreezeStamps()) {
            $this->subscribers[] = new StampSubscriber($activeUser);
        }
        if ($config->getAccessControlFreeze()){
            $this->subscribers[] = new FreezeSubscriber($activeUser);
        }
        if ($config->getAccessControlThaw()){
            $this->subscribers[] = new ThawSubscriber($activeUser);
        }
        $this->filters = array('freeze' => 'Sds\DoctrineExtensions\Freeze\Filter\Freeze');
    }
}
