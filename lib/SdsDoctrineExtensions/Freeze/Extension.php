<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Freeze;

use SdsDoctrineExtensions\AbstractExtension;
use SdsDoctrineExtensions\Freeze\Subscriber\Freeze as FreezeSubscriber;
use SdsDoctrineExtensions\Freeze\Subscriber\FreezeStamp as FreezeStampSubscriber;
use SdsDoctrineExtensions\Freeze\AccessControl\Subscribers\Freeze as AccessControlFreezeSubscriber;
use SdsDoctrineExtensions\Freeze\AccessControl\Subscribers\Thaw as AccessControlThawSubscriber;

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
        $activeUser = $config->getActiveUser();

        $this->config = $config;

        $this->annotations = array('SdsDoctrineExtensions\Freeze\Mapping\Annotation' => __DIR__.'/../../');

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
        $this->filters = array('freeze' => 'SdsDoctrineExtensions\Freeze\Filter\Freeze');
    }
}
