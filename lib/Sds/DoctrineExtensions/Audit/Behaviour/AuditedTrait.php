<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Audit\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\Common\Audit\AuditInterface;

/**
 * Implements Sds\Common\Audit\AuditedInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait AuditedTrait {

    /**
     * @ODM\EmbedMany(
     *   targetDocument="Sds\DoctrineExtensions\Audit\Model\Audit"
     * )
     * @Sds\UiHints(label = "Audits")
     */
    protected $audits = array();

    /**
     *
     * @param \Sds\Common\Audit\AuditInterface $audit
     */
    public function addAudit(AuditInterface $audit){
        $this->audits[] = $audit;
    }

    /**
     *
     * @return array
     */
    public function getAudits(){
        return $this->audits;
    }
}
