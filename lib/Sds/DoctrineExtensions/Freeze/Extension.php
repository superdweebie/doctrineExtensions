<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Freeze;

use Sds\DoctrineExtensions\AbstractExtension;
use Sds\DoctrineExtensions\Freeze\Subscriber\Freeze as FreezeSubscriber;
use Sds\DoctrineExtensions\Freeze\Subscriber\FreezeStamp as FreezeStampSubscriber;
use Sds\DoctrineExtensions\Freeze\AccessControl\Subscriber\Freeze as AccessControlFreezeSubscriber;
use Sds\DoctrineExtensions\Freeze\AccessControl\Subscriber\Thaw as AccessControlThawSubscriber;

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

        $this->annotations = array(
            'Sds\DoctrineExtensions\Freeze\Mapping\Annotation' => __DIR__.'/../../',
            'Sds\DoctrineExtensions\AccessControl\Mapping\Annotation' => __DIR__.'/../../'
        );

        $this->subscribers = array(new FreezeSubscriber($config->getAnnotationReader()));
        if ($config->getUseFreezeStamps()) {
            $this->subscribers[] = new FreezeStampSubscriber($activeUser);
        }
        if ($config->getAccessControlFreeze()){
            $this->subscribers[] = new AccessControlFreezeSubscriber($activeUser);
        }
        if ($config->getAccessControlThaw()){
            $this->subscribers[] = new AccessControlThawSubscriber($activeUser);
        }
        $this->filters = array('freeze' => 'Sds\DoctrineExtensions\Freeze\Filter\Freeze');
    }
}
