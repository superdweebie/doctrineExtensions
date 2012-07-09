<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\State;

use SdsDoctrineExtensions\AbstractExtension;
use SdsDoctrineExtensions\State\Subscriber;

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

        $this->annotations = array(
            'SdsDoctrineExtensions\State\Mapping\Annotation' => __DIR__.'/../../',
            'SdsDoctrineExtensions\AccessControl\Mapping\Annotation' => __DIR__.'/../../'
        );

        $this->subscribers = array(new Subscriber\State($config->getAnnotationReader()));
        if ($config->getAccessControlStateChange()){
            $this->subscribers[] = new AccessControl\Subscriber\StateChange($config->getActiveUser());
        }

        $this->filters = array('state' => 'SdsDoctrineExtensions\State\Filter\State');
    }
}