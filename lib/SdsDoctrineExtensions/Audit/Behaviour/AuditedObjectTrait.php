<?php

namespace SdsDoctrineExtensions\Audit\Behaviour;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use SdsDoctrineExtensions\Audit\Model\Audit;
use SdsCommon\Audit\AuditInterface;

trait AuditedObjectTrait {
  
     /**
    * @ODM\EmbedMany(
    *   targetDocument="SdsDoctrineExtensions\Audit\Model\Audit"
    * )
    */     
    protected $audits = array();
    
    public function addAudit(AuditInterface $audit){
        if(!$audit instanceof Audit){
            throw new \InvalidArgumentException();
        }
        $this->audits[] = $audit;
    }
    
    public function getAudits(){
        return $this->audits;
    }
}
