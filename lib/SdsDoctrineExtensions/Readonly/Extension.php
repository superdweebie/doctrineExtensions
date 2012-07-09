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

    public function __construct($config){

        $this->configClass = __NAMESPACE__ . '\ExtensionConfig';
        parent::__construct($config);
        $config = $this->getConfig();

        $this->annotations = array('SdsDoctrineExtensions\Readonly\Mapping\Annotation' => __DIR__.'/../../');

        $this->subscribers = array(new ReadonlySubscriber($config->getAnnotationReader()));
    }
}
