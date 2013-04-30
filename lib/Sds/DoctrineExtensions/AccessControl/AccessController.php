<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Sds\Common\Identity\RoleAwareIdentityInterface;
use Sds\DoctrineExtensions\DocumentManagerAwareInterface;
use Sds\DoctrineExtensions\DocumentManagerAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Defines methods for a manager object to check permssions
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AccessController implements ServiceLocatorAwareInterface, DocumentManagerAwareInterface {

    use ServiceLocatorAwareTrait;
    use DocumentManagerAwareTrait;

    protected $permissions = [];

    protected $roles;

    public function enableReadFilter(){
        $filter = $this->documentManager->getFilterCollection()->enable('readAccessControl');
        $filter->setAccessController($this);
    }

    /**
     * Determines if an action can be done by the current Identity
     *
     * @param type $document
     * @param string | ActionInterface $action
     * @return IsAllowedResult
     */
    public function isAllowed($action, ClassMetadata $metadata = null, $document = null){

        $result = new IsAllowedResult(false);
        if (!isset($metadata)){
            $metadata = $this->documentManager->getClassMetadata(get_class($document));
        }

        if (!isset($metadata->permissions)){
            return $result;
        }

        if ( !isset($this->permissions[$metadata->name])){
            $this->permissions[$metadata->name] = [];
        }

        foreach($metadata->permissions as $index => $permissionMetadata){

            if ( !isset($this->permissions[$metadata->name][$index])){
                $factory = $permissionMetadata['factory'];
                $this->permissions[$metadata->name][$index] = $factory::get($metadata, $permissionMetadata['options']);
            }

            $permission = $this->permissions[$metadata->name][$index];
            $newResult = $permission->isAllowed($this->getRoles(), $action);
            $isAllowed = $newResult->getIsAllowed();
            if ( ! isset($isAllowed)){
                continue;
            }
            $result->setIsAllowed($isAllowed);

            $new = $newResult->getNew();
            if (isset($new)){
                $result->setNew(array_merge($new, $newResult->getNew()));
            }

            $old = $newResult->getOld();
            if (isset($old)){
                $result->setOld(array_merge($old, $newResult->getOld()));
            }
        }

        if (isset($document)){
            if (count($result->getNew()) > 0){
                foreach ($result->getNew() as $field => $value){
                    if ($metadata->reflFields[$field]->getValue($document) != $value){
                        $result->setIsAllowed(false);
                        return $result;
                    }
                }
            }

            if (count($result->getOld()) > 0){
                $changeSet = $this->documentManager->getUnitOfWork()->getDocumentChangeSet($document);
                foreach ($result->getOld() as $field => $value){
                    if ($changeSet[$field][0] != $value){
                        $result->setIsAllowed(false);
                        return $result;
                    }
                }
            }
        }

        return $result;
    }

    protected function getRoles($flush = false){

        if (!isset($this->roles) || $flush){
            if ($this->serviceLocator->has('Sds\DoctrineExtensions\Identity') &&
                $identity = $this->serviceLocator->get('Sds\DoctrineExtensions\Identity')
            ){
                if ($identity instanceof RoleAwareIdentityInterface){
                    $this->roles = $identity->getRoles();
                } else {
                    $this->roles = [];
                }
            } else {
                $this->roles = [];
            }
        }
        return $this->roles;
    }
}
