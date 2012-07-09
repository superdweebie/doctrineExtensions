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

    public function __construct($config){

        $this->configClass = __NAMESPACE__ . '\ExtensionConfig';
        parent::__construct($config);
        $config = $this->getConfig();

        $this->subscribers = array(new Subscriber\Workflow());

        $this->documents = array('SdsDoctrineExtensions\Workflow\Model' => __DIR__.'\..\Model');
    }
}