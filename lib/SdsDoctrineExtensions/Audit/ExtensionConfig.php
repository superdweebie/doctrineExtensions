<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Audit;

use SdsDoctrineExtensions\AbstractExtensionConfig;
use SdsDoctrineExtensions\AnnotationReaderAwareInterface;
use SdsDoctrineExtensions\AnnotationReaderAwareTrait;
use SdsCommon\User\ActiveUserAwareInterface;
use SdsCommon\User\ActiveUserAwareTrait;

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
    protected $auditClass = 'SdsDoctrineExtensions\Audit\Model\Audit';

    /**
     * {@inheritdoc}
     */
    protected $dependencies = array(
        'SdsDoctrineExtensions\DoNotHardDelete' => null,
        'SdsDoctrineExtensions\Readonly' => null
    );

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
