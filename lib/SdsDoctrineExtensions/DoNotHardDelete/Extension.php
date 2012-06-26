<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\DoNotHardDelete;

use SdsDoctrineExtensions\AbstractExtension;
use SdsDoctrineExtensions\DoNotHardDelete\Subscriber\DoNotHardDelete as DoNotHardDeleteSubscriber;

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

        $this->annotations = array('SdsDoctrineExtensions\DoNotHardDelete\Mapping\Annotation' => __DIR__.'/../../');

        $this->subscribers = array(new DoNotHardDeleteSubscriber($config->getAnnotationReader()));
    }
}
