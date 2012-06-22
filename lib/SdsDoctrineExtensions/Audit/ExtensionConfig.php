<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Audit;

use SdsDoctrineExtensions\AbstractExtensionConfig;
use SdsDoctrineExtensions\AnnotationReaderConfigInterface;
use SdsDoctrineExtensions\AnnotationReaderConfigTrait;
use SdsDoctrineExtensions\ActiveUserConfigInterface;
use SdsDoctrineExtensions\ActiveUserConfigTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig implements
    AnnotationReaderConfigInterface,
    ActiveUserConfigInterface
{

    use AnnotationReaderConfigTrait;
    use ActiveUserConfigTrait;

    /**
     * Defines the audit class to use
     *
     * @var boolean
     */
    protected $auditClass = 'SdsDoctrineExtensions\Audit\Model\Audit';

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
