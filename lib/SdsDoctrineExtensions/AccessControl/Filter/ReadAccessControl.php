<?php

namespace SdsDoctrineExtensions\AccessControl\Filter;

use Doctrine\ODM\MongoDB\Mapping\ClassMetaData,
    Doctrine\ODM\MongoDB\Query\Filter\BsonFilter,
    SdsDoctrineExtensions\ActiveUser\Behaviour\ActiveUser as ActiveUserTrait;
    
class MyLocaleFilter extends BsonFilter
{
    use ActiveUserTrait;
    
    protected $activeUser;
    
    protected $documentAccessControlTrait = 'SdsDoctrineExtensions\AccessControl\Behaviour\DocumentAccessControl';
    
    
    public function addFilterConstraint(ClassMetadata $targetDocument)
    {
        // Check if the document exhibits the DocumentAccessControl trait
        
        if(!Utils::checkForTrait($targetDocument, $this->documentAccessControlTrait)){
            return [];
        } 
            
        return ['permissions' => $this->AssemleQuery()];
    }
    
    protected function AssembleQuery(){
        $roles = json_encode($this->activeUser->getRoles());
        return '
            permissions: {
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
