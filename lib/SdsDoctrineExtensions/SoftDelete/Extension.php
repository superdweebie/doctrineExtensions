<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete;

use SdsDoctrineExtensions\AbstractExtension;
use SdsDoctrineExtensions\SoftDelete\Subscriber\SoftDelete as SoftDeleteSubscriber;
use SdsDoctrineExtensions\SoftDelete\Subscriber\SoftStamp as SoftStampSubscriber;

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

        $this->annotations = array('SdsDoctrineExtensions\SoftDelete\Mapping\Annotation' => __DIR__.'/../../');

        $this->subscribers = array(new SoftDeleteSubscriber($config->getAnnoationReader()));
        if ($config->getUseSoftDeleteStamps()) {
            $this->subscribers[] = new SoftStampSubscriber($config->getActiveUser());
        }

        $this->filters = array('softDelete' => 'SdsDoctrineExtensions\SoftDelete\Filter\SoftDelete');
    }
}
