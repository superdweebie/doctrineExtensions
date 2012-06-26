<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Serializer;

use SdsDoctrineExtensions\AbstractExtension;
use SdsDoctrineExtensions\Serializer\Subscriber\Serializer as SerializerSubscriber;

/**
 * Defines the resouces this extension provies
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    /**
     *
     * @param \SdsDoctrineExtensions\Serializer\ExtensionConfig $config
     */
    public function __construct(ExtensionConfig $config){
        $this->config = $config;

        $this->annotations = array('SdsDoctrineExtensions\Serializer\Mapping\Annotation' => __DIR__.'/../../');

        $this->subscribers = array(new SerializerSubscriber($config->getAnnotationReader()));
    }
}
