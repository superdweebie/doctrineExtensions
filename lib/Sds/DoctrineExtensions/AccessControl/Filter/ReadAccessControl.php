<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl\Filter;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Query\Filter\BsonFilter;
use Sds\Common\AccessControl\Constant\Action;
use Sds\DoctrineExtensions\AccessControl\AccessController;

/**
 * When this filter is enabled, will filter out all documents
 * the active identity does not have permission to read.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ReadAccessControl extends BsonFilter
{

    /**
     *
     * @param array $roles
     */
    public function setRoles(array $roles = []){
        $this->parameters['roles'] = $roles;
    }

    /**
     *
     * @return array
     */
    public function getRoles(){
        return isset($this->parameters['roles']) ? $this->parameters['roles'] : null;
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Mapping\ClassMetadata $targetDocument
     * @return array
     */
    public function addFilterCriteria(ClassMetadata $targetDocument)
    {
        if (AccessController::isAccessControlEnabled($targetDocument, Action::read)){
            $return = array(
                'permissions' => array(
                    '$elemMatch' => array(
                        'action' => Action::read,
                        'role' => array(
                            '$in' => $this->parameters['roles']
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
