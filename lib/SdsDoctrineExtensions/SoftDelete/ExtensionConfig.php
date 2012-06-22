<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\SoftDelete;

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
     * Flag if the SoftDelete\Subscriber\Stamp should be registed to stamp
     * documents on softDelete and Restore events
     *
     * @var boolean
     */
    protected $useSoftDeleteStamps = false;

    /**
     *
     * @return boolean
     */
    public function getUseSoftDeleteStamps() {
        return $this->useSoftDeleteStamps;
    }

    /**
     *
     * @param boolean $useSoftDeleteStamps
     */
    public function setUseSoftDeleteStamps($useSoftDeleteStamps) {
        $this->useSoftDeleteStamps = (boolean) $useSoftDeleteStamps;
    }
}
