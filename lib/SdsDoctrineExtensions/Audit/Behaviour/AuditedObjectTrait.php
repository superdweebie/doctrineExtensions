<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Audit\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsCommon\Audit\AuditInterface;

/**
 * Implements SdsCommon\Audit\AuditedObjectInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait AuditedObjectTrait {

    /**
     * @ODM\EmbedMany(
     *   targetDocument="SdsDoctrineExtensions\Audit\Model\Audit"
     * )
     */
    protected $audits = array();

    /**
     *
     * @param \SdsCommon\Audit\AuditInterface $audit
     * @throws \InvalidArgumentException
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
