<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\AccessControl;

use Sds\Common\AccessControl\PermissionInterface;

/**
 * Implements Sds\Common\AccessControl\PermissionInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class BasicPermission implements PermissionInterface
{

    const all = 'all';

    protected $roles;

    protected $allow;

    protected $deny;

    public function __construct(array $roles, array $allow, array $deny){
        $this->roles = $roles;
        $this->allow = $allow;
        $this->deny = $deny;
    }

    public function isAllowed(array $roles, $action) {
        if (in_array(self::all, $this->roles) || count(array_intersect($roles, $this->roles)) > 0){
            if (in_array($action, $this->allow) ||
               (in_array(self::all, $this->allow) && ! in_array($action, $this->deny))
            ){
                return new IsAllowedResult(true);
            }
            if (in_array($action, $this->deny) || in_array(self::all, $this->deny)){
                return new IsAllowedResult(false);
            }
        }

        return new IsAllowedResult;
    }
}

