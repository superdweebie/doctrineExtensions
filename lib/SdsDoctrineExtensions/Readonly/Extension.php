<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Readonly;

use SdsDoctrineExtensions\AbstractExtension;
use SdsDoctrineExtensions\Readonly\Subscriber\Readonly as ReadonlySubscriber;

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

        $this->annotations = array('SdsDoctrineExtensions\Readonly\Mapping\Annotation' => __DIR__.'/../../');

        $this->subscribers = array(new ReadonlySubscriber($config->getAnnoationReader()));
    }
}
