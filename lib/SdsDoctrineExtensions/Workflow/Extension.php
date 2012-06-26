<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Workflow;

use SdsDoctrineExtensions\AbstractExtension;
use SdsDoctrineExtensions\Workflow\Subscriber;

/**
 * Defines the resouces this extension provies
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    /**
     *
     * @param \SdsDoctrineExtensions\UiHints\ExtensionConfig $config
     */
    public function __construct(ExtensionConfig $config){
        $this->config = $config;

        $this->subscribers = array(new Subscriber\Workflow());
    }
}
