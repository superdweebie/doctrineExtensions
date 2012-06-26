<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\AccessControl\Filter;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Query\Filter\BsonFilter;
use SdsCommon\AccessControl\Constant\Action;
use SdsCommon\AccessControl\AccessControlledInterface;
use SdsCommon\AccessControl\RoleAwareUserInterface;

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
     * @param \SdsCommon\AccessControl\RoleAwareUserInterface $activeUser
     */
    public function setActiveUser(RoleAwareUserInterface $activeUser){
        $this->parameters['activeUser'] = $activeUser;
    }

    /**
     *
     * @return \SdsCommon\AccessControl\RoleAwareUserInterface
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
        if($targetDocument instanceof AccessControlledInterface &&
            $targetDocument instanceof StateAwareInterface
        ){
            return array(
                'permissions' => array(
                    '$elemMatch' => array(
                        'state' => 'state',
                        'action' => Action::read,
                        'role' => array(
                            '$in' => $this->parameters['activeUser']->getRoles()
                        )
                    )
                )
            );
        }
        return array();
    }
}
