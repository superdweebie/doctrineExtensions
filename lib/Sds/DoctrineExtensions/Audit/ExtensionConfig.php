<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Audit;

use Sds\DoctrineExtensions\AbstractExtensionConfig;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\Common\User\ActiveUserAwareInterface;
use Sds\Common\User\ActiveUserAwareTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig implements
    AnnotationReaderAwareInterface,
    ActiveUserAwareInterface
{

    use AnnotationReaderAwareTrait;
    use ActiveUserAwareTrait;

    /**
     * Defines the audit class to use
     *
     * @var boolean
     */
    protected $auditClass = 'Sds\DoctrineExtensions\Audit\Model\Audit';

    /**
     *
     * @return string
     */
    public function getAuditClass() {
        return $this->auditClass;
    }

    /**
     *
     * @param type $auditClass
     */
    public function setAuditClass($auditClass) {
        $this->auditClass = (string) $auditClass;
    }
}
