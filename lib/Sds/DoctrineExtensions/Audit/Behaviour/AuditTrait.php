<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Audit\Behaviour;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * Implements Sds\Common\Audit\AuditInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
trait AuditTrait
{
    /**
     * @ODM\Field(type="string")
     * @Sds\Readonly
     */
    protected $oldValue;

    /**
     * @ODM\Field(type="string")
     * @Sds\Readonly
     */
    protected $newValue;

    /**
     * @ODM\Field(type="timestamp")
     * @Sds\Readonly
     */
    protected $changedOn;

    /**
     * @ODM\Field(type="string")
     * @Sds\Readonly
     * @Sds\ValidatorGroup(@Sds\Validator(class = "Sds\Common\Validator\IdentifierValidator"))
     */
    protected $changedBy;

    /**
     *
     * @param string $oldValue
     * @param string $newValue
     * @param timestamp $changedOn
     * @param string $changedBy
     */
    public function __construct($oldValue, $newValue, $changedOn, $changedBy){
        $this->oldValue = (string) $oldValue;
        $this->newValue = (string) $newValue;
        $this->changedOn = $changedOn;
        $this->changedBy = (string) $changedBy;
    }

    /**
     *
     * @return string
     */
    public function getOldValue() {
        return $this->oldValue;
    }

    /**
     *
     * @return string
     */
    public function getNewValue() {
        return $this->newValue;
    }

    /**
     *
     * @return timestamp
     */
    public function getChangedOn() {
        return $this->changedOn;
    }

    /**
     *
     * @return username
     */
    public function getChangedBy() {
        return $this->changedBy;
    }
}
