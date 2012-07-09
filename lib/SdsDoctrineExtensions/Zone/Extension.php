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

    public function __construct($config){

        $this->configClass = __NAMESPACE__ . '\ExtensionConfig';
        parent::__construct($config);
        $config = $this->getConfig();

        $this->annotations = array('SdsDoctrineExtensions\Zone\Mapping\Annotation' => __DIR__.'/../../');

        $this->subscribers = array(new ZoneSubscriber($config->getAnnotationReader()));

        $this->filters = array('zone' => 'SdsDoctrineExtensions\Zone\Filter\Zone');
    }
}
