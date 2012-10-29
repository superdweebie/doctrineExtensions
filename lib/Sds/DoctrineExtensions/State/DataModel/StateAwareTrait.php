<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State\DataModel;

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
     * @Sds\State
     * @Sds\AccessControl(@Sds\AccessControl\Update(false))
     * @Sds\Validator(class = "Sds\Common\Validator\IdentifierValidator")
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
