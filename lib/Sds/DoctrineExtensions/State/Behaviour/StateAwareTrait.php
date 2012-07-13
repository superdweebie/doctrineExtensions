<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State\Behaviour;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotations as Sds;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait StateAwareTrait{

    /**
     * @ODM\Field(type="string")
     * @ODM\Index
     * @Sds\Audit
     * @Sds\StateField
     * @Sds\DoNotAccessControlUpdate
     * @Sds\UiHints(label = "State")
     * @Sds\Validator(class = "Sds\DoctrineExtensions\Validator\Validator\StandardName")
     */
    protected $state;

    /**
     * Set the current resource state
     *
     * @param string $state
     */
    public function setState($state){
        $this->state = (string) $state;
    }

    /**
     * @return string
     */
    public function getState(){
        return $this->state;
    }
}
