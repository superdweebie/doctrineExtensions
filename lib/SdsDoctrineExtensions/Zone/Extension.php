<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Zone;

use SdsDoctrineExtensions\AbstractExtension;
use SdsDoctrineExtensions\Zone\Subscriber\Zone as ZoneSubscriber;

/**
 * Defines the resouces this extension provies
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    /**
     *
     * @param \SdsDoctrineExtensions\Zone\ExtensionConfig $config
     */
    public function __construct(ExtensionConfig $config){
        $this->config = $config;

        $this->annotations = array('SdsDoctrineExtensions\Zone\Mapping\Annotation' => __DIR__.'/../../');

        $this->subscribers = array(new ZoneSubscriber($config->getAnnoationReader()));

        $this->filters = array('zone' => 'SdsDoctrineExtensions\Zone\Filter\Zone');
    }
}
