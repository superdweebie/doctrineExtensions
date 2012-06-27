<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete;

use SdsDoctrineExtensions\AbstractExtension;
use SdsDoctrineExtensions\SoftDelete\Subscriber;
use SdsDoctrineExtensions\SoftDelete\AccessControl;


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

        $activeUser = $config->getActiveUser();

        $this->config = $config;

        $this->annotations = array(
            'SdsDoctrineExtensions\SoftDelete\Mapping\Annotation' => __DIR__.'/../../',
            'SdsDoctrineExtensions\AccessControl\Mapping\Annotation' => __DIR__.'/../../'
        );

        $this->subscribers = array(new Subscriber\SoftDelete($config->getAnnotationReader()));
        if ($config->getUseSoftDeleteStamps()) {
            $this->subscribers[] = new Subscriber\SoftStamp($activeUser);
        }
        if ($config->getAccessControlSoftDelete()){
            $this->subscribers[] = new AccessControl\Subscriber\SoftDelete($activeUser);
        }
        if ($config->getAccessControlRestore()){
            $this->subscribers[] = new AccessControl\Subscriber\Restore($activeUser);
        }

        $this->filters = array('softDelete' => 'SdsDoctrineExtensions\SoftDelete\Filter\SoftDelete');
    }
}
