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

        $this->annotations = array('SdsDoctrineExtensions\Freeze\Mapping\Annotation' => __DIR__.'/../../');

        $this->subscribers = array(new FreezeSubscriber($config->getAnnoationReader()));
        if ($config->getUseFreezeStamps()) {
            $this->subscribers[] = new FreezeStampSubscriber($config->getActiveUser());
        }

        $this->filters = array('freeze' => 'SdsDoctrineExtensions\Freeze\Filter\Freeze');
    }
}
