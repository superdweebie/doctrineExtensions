<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Zone;

use Sds\DoctrineExtensions\AbstractExtension;
use Sds\DoctrineExtensions\Zone\Subscriber\Zone as ZoneSubscriber;

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

        $this->annotations = array('Sds\DoctrineExtensions\Zone\Mapping\Annotation' => __DIR__.'/../../../');

        $this->subscribers = array(new ZoneSubscriber($config->getAnnotationReader()));

        $this->filters = array('zone' => 'Sds\DoctrineExtensions\Zone\Filter\Zone');
    }
}
