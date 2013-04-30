<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State\AccessControl;

use Sds\Common\AccessControl\PermissionInterface;
use Sds\DoctrineExtensions\AccessControl\IsAllowedResult;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class StatePermission implements PermissionInterface
{

    const all = 'all';

    protected $roles;

    protected $allow;

    protected $deny;

    protected $state;

    protected $stateField;

    public function __construct(array $roles, array $allow, array $deny, $state, $stateField){
        $this->roles = $roles;
        $this->allow = $allow;
        $this->deny = $deny;
        $this->state = (string) $state;
        $this->stateField = (string) $stateField;
    }

    public function isAllowed(array $roles, $action) {
        if (in_array(self::all, $this->roles) || count(array_intersect($roles, $this->roles)) > 0){
            if (in_array($action, $this->allow) ||
               (in_array(self::all, $this->allow) && ! in_array($action, $this->deny))
            ){
                return new IsAllowedResult(true, null, [$this->stateField => $this->state]);
            }
            if (in_array($action, $this->deny) || in_array(self::all, $this->deny)){
                return new IsAllowedResult(false, null, [$this->stateField => $this->state]);
            }
        }
        return new IsAllowedResult;
    }
}
