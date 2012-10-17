<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

use Sds\Common\AccessControl\AccessController as CommonAccessController;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;

/**
 * Defines methods for a manager object to check permssions
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AccessController extends CommonAccessController{

    /**
     * Determine if access control is enabled on a particular document type for a
     * paricular action.
     *
     * In general, the following are processed in order:
     * 
     * If a value is set for the specific action, then return that value.
     * If the action is a transition, and a value is set for DefaultTransition, then return that value.
     * If a value is set for DefaultValue, then return that value.
     * If the document implements AccessControlledInterface, then return true.
     *
     * @param \Doctrine\Common\Persistence\Mapping\ClassMetadata $metadata
     * @param string $action
     * @param boolean $isTransition
     * @return boolean
     */
    static public function isAccessControlEnabled(ClassMetadata $metadata, $action, $isTransition = false){

        if ($metadata->reflClass->implementsInterface('Sds\Common\AccessControl\AccessControlledInterface')){
            if (isset($metadata->accessControl['document'])){
                if (isset($metadata->accessControl['document'][$action])){
                    return (boolean) $metadata->accessControl['document'][$action];
                } else {
                    if ($isTransition){
                        if (isset($metadata->accessControl['document']['defaultTransition'])){
                            return (boolean) $metadata->accessControl['document']['defaultTransition'];
                        } else {
                            if (isset($metadata->accessControl['document']['defaultValue'])){
                                return (boolean) $metadata->accessControl['document']['defaultValue'];
                            }
                            return true;
                        }
                    } else {
                        if (isset($metadata->accessControl['document']['defaultValue'])){
                            return (boolean) $metadata->accessControl['document']['defaultValue'];
                        }
                        return true;
                    }
                }
            }
            return true;
        }

        return false;
    }
}
