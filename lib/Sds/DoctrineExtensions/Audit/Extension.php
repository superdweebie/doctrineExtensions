<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Audit;

use Sds\DoctrineExtensions\AbstractExtension;
use Sds\DoctrineExtensions\Audit\Subscriber\Audit as AuditSubscriber;

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
            'Sds\DoctrineExtensions\Audit\Mapping\Annotation' => __DIR__.'/../../../',
        );

        $this->subscribers = array(new AuditSubscriber(
            $config->getAnnotationReader(),
            $config->getActiveUser(),
            $config->getAuditClass()
        ));

        $reflection = new \ReflectionClass($config->getAuditClass());
        $this->documents = array($reflection->getNamespaceName() => dirname($reflection->getFileName()));
    }
}
