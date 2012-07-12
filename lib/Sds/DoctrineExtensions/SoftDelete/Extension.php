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

        $activeUser = $config->getActiveUser();

        $this->subscribers = array(new Subscriber($config->getAnnotationReader()));
        if ($config->getUseSoftDeleteStamps()) {
            $this->subscribers[] = new StampSubscriber($activeUser);
        }
        if ($config->getAccessControlSoftDelete()){
            $this->subscribers[] = new AccessControl\SoftDeleteSubscriber($activeUser);
        }
        if ($config->getAccessControlRestore()){
            $this->subscribers[] = new AccessControl\RestoreSubscriber($activeUser);
        }

        $this->filters = array('softDelete' => 'Sds\DoctrineExtensions\SoftDelete\Filter\SoftDelete');
    }
}
