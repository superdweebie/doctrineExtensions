<?php

namespace SdsDoctrineExtensions\Audit\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM,
    SdsDoctrineExtensions\Audit\Model\Audit as AuditModel;

trait AuditTrait {
  
     /**
    * @ODM\EmbedMany(
    *   targetDocument="SdsDoctrineExtensions\Audit\Model\Audit"
    * )
    */     
    protected $audits = array();
    
    public function addAudit(AuditModel $audit){
        $this->audits[] = $audit;
    }
    
    public function getAudits(){
        return $this->audits;
    }
}
