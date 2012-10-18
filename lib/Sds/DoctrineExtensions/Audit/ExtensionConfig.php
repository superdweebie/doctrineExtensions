<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Audit;

use Sds\DoctrineExtensions\AbstractExtensionConfig;
use Sds\DoctrineExtensions\IdentityNameExtensionConfigTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig
{

    use IdentityNameExtensionConfigTrait;

    /**
     * Defines the audit class to use
     *
     * @var boolean
     */
    protected $auditClass = 'Sds\DoctrineExtensions\Audit\DataModel\Audit';

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
