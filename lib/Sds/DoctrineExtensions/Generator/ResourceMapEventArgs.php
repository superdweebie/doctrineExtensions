<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Generator;

use Doctrine\Common\EventArgs as BaseEventArgs;

/**
 * Arguments for resource map events
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ResourceMapEventArgs extends BaseEventArgs {

    protected $resourceMap;

    public function __construct(ResourceMap $resourceMap) {
        $this->resourceMap = $resourceMap;
    }

    public function getResourceMap() {
        return $this->resourceMap;
    }
}