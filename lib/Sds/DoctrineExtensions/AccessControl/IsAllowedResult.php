<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

/**
 * Implements Sds\Common\AccessControl\PermissionInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class IsAllowedResult
{

    protected $isAllowed;

    protected $old;

    protected $new;

    public function getIsAllowed() {
        return $this->isAllowed;
    }

    public function setIsAllowed($isAllowed) {
        $this->isAllowed = (boolean) $isAllowed;
    }

    public function getOld() {
        return $this->old;
    }

    public function setOld(array $old) {
        $this->old = $old;
    }

    public function getNew() {
        return $this->new;
    }

    public function setNew(array $new) {
        $this->new = $new;
    }

    public function __construct($isAllowed = null, array $old = null, array $new = null){
        $this->isAllowed = isset($isAllowed) ? (boolean) $isAllowed : null;
        $this->old = $old;
        $this->new = $new;
    }

    public function hasCriteria(){
        if (isset($this->new) || isset($this->old)){
            return true;
        }
        return false;
    }
}

