<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl\Filter;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Query\Filter\BsonFilter;
use Sds\Common\User\RoleAwareUserInterface;
use Sds\DoctrineExtensions\AccessControl\Constant\Action;

/**
 * When this filter is enabled, will filter out all documents
 * the activeUser does not have permission to read.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ReadAccessControl extends BsonFilter
{

    /**
     *
     * @param \Sds\Common\AccessControl\RoleAwareUserInterface $activeUser
     */
    public function setActiveUser(RoleAwareUserInterface $activeUser){
        $this->parameters['activeUser'] = $activeUser;
    }

    /**
     *
     * @return \Sds\Common\AccessControl\RoleAwareUserInterface
     */
    public function getActiveUser(){
        return isset($this->parameters['activeUser']) ? $this->parameters['activeUser'] : null;
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadata $targetDocument
     * @return type
     */
    public function addFilterCriteria(ClassMetadata $targetDocument)
    {
        if($targetDocument->reflClass->implementsInterface('Sds\Common\AccessControl\AccessControlledInterface')){
            $return = array(
                'permissions' => array(
                    '$elemMatch' => array(
                        'action' => Action::read,
                        'role' => array(
                            '$in' => $this->parameters['activeUser']->getRoles()
                        )
                    )
                )
            );
            if($targetDocument->reflClass->implementsInterface('Sds\Common\State\StateAwareInterface')){
                $return['permissions']['$elemMatch']['stateEqualToParent'] = true;
            }
            return $return;
        }
        return array();
    }
}
