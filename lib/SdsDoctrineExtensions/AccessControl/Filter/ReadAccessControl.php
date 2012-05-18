<?php

namespace SdsDoctrineExtensions\AccessControl\Filter;

use Doctrine\ODM\MongoDB\Mapping\ClassMetaData,
    Doctrine\ODM\MongoDB\Query\Filter\BsonFilter,
    SdsDoctrineExtensions\Common\Utils,
    SdsDoctrineExtensions\AccessControl\Model\Permission;
    
class ReadAccessControl extends BsonFilter
{  
    protected $documentAccessControlTrait = 'SdsDoctrineExtensions\AccessControl\Behaviour\DocumentAccessControl';
       
    public function addFilterCriteria(ClassMetadata $targetDocument)
    {
        // Check if the document exhibits the DocumentAccessControl trait
        
        if(!Utils::checkForTrait($targetDocument->name, $this->documentAccessControlTrait)){
            return [];
        } 
            
        return ['permissions' => $this->AssembleQuery()];
    }
    
    protected function AssembleQuery(){
        $roles = json_encode($this->getParameter('activeUser')->getRoles());
        return '
            {
                $eleMatch: {
                    state: state,
                    action: '.Permission::ACTION_READ.',
                    role: {
                        $or: '.$roles.'
                    }
                }
            }
        ';
    }
}
