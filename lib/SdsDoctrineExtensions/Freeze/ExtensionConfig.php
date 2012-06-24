<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Freeze;

use SdsDoctrineExtensions\AbstractExtensionConfig;
use SdsDoctrineExtensions\AnnotationReaderConfigInterface;
use SdsDoctrineExtensions\AnnotationReaderConfigTrait;
use SdsDoctrineExtensions\ActiveUserConfigInterface;
use SdsDoctrineExtensions\ActiveUserConfigTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig implements
    AnnotationReaderConfigInterface,
    ActiveUserConfigInterface
{

    use AnnotationReaderConfigTrait;
    use ActiveUserConfigTrait;

    /**
     * Flag if the Freeze\Subscriber\Stamp should be registed to stamp
     * documents on Freeze and Thaw events
     *
     * @var boolean
     */
    protected $useFreezeStamps = false;

    /**
     *
     * @return boolean
     */
    public function getUseFreezeStamps() {
        return $this->useFreezeStamps;
    }

    /**
     *
     * @param boolean $useFreezeStamps
     */
    public function setUseFreezeStamps($useFreezeStamps) {
        $this->useFreezeStamps = (boolean) $useFreezeStamps;
    }
}
