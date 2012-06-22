<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Audit;

use SdsDoctrineExtensions\AbstractExtension;
use SdsDoctrineExtensions\Audit\Subscriber\Audit as AuditSubscriber;

/**
 * Defines the resouces this extension provies
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    /**
     *
     * @param \SdsDoctrineExtensions\Audit\ExtensionConfig $config
     */
    public function __construct(ExtensionConfig $config){
        $this->config = $config;

        $this->annotations = array(
            'SdsDoctrineExtensions\Audit\Mapping\Annotation' => __DIR__.'/../../',
            'SdsDoctrineExtensions\DoNotHardDelete\Mapping\Annotation' => __DIR__.'/../../',
            'SdsDoctrineExtensions\Readonly\Mapping\Annotation' => __DIR__.'/../../'
        );

        $this->subscribers = array(new AuditSubscriber(
            $config->getAnnoationReader(),
            $config->getActiveUser(),
            $config->getAuditClass()
        ));

        $reflection = new \ReflectionClass($config->getAuditClass());
        $this->documents = array($reflection->getNamespaceName() => dirname($reflection->getFileName()));
    }
}
