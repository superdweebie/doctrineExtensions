<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Audit;

use Sds\DoctrineExtensions\AbstractExtensionConfig;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig
{

    /**
     *
     * @var string
     */
    protected $identityName;

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

    /**
     *
     * @return string
     */
    public function getIdentityName() {
        return $this->identityName;
    }

    /**
     *
     * @param string $identityName
     */
    public function setIdentityName($identityName) {
        $this->identityName = (string) $identityName;
    }
}
