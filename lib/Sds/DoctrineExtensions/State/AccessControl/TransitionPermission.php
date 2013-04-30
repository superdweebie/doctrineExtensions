<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State\AccessControl;

use Sds\Common\AccessControl\PermissionInterface;
use Sds\Common\State\Transition;
use Sds\DoctrineExtensions\AccessControl\IsAllowedResult;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class TransitionPermission implements PermissionInterface
{

    const all = 'all';

    protected $roles;

    protected $allow;

    protected $deny;

    protected $state;

    protected $stateField;

    public function __construct(array $roles, array $allow, array $deny, $stateField){
        $this->roles = $roles;
        $this->allow = $allow;
        $this->deny = $deny;
        $this->stateField = (string) $stateField;
    }

    public function isAllowed(array $roles, $action) {
        if (in_array(self::all, $this->roles) || count(array_intersect($roles, $this->roles)) > 0){
            if (in_array($action, $this->allow) ||
               (in_array(self::all, $this->allow) && ! in_array($action, $this->deny))
            ){
                $transition = Transition::fromString($action);
                return new IsAllowedResult(
                    true,
                    [$this->stateField => $transition->getFrom()],
                    [$this->stateField => $transition->getTo()]
                );
            }
            if (in_array($action, $this->deny) || in_array(self::all, $this->deny)){
                $transition = Transition::fromString($action);
                return new IsAllowedResult(
                    false,
                    [$this->stateField => $transition->getFrom()],
                    [$this->stateField => $transition->getTo()]
                );
            }
        }
        return new IsAllowedResult;
    }
}
