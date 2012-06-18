<?php

namespace SdsDoctrineExtensions\AccessControl\Filter;

use Doctrine\ODM\MongoDB\Mapping\ClassMetaData;
use Doctrine\ODM\MongoDB\Query\Filter\BsonFilter;
use SdsDoctrineExtensions\AccessControl\Model\Permission;
use SdsCommon\AccessControl\ObjectInterface;

class ReadAccessControl extends BsonFilter
{         
    public function addFilterCriteria(ClassMetadata $targetDocument)
    {
        // Check if the document exhibits the DocumentAccessControl trait
        if($targetDocument instanceof ObjectInterface){
            return array('permissions' => $this->AssembleQuery());
        } 
        return array(); 
    }
    
    protected function AssembleQuery(){
        $roles = json_encode($this->getParameter('activeUser')->getRoles());
        return '
            {
                $elemMatch: {
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
