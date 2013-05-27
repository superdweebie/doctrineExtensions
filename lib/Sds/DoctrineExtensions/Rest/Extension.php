<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Rest;

use Sds\DoctrineExtensions\AbstractExtension;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Extension extends AbstractExtension {

    protected $serviceManagerConfig = [
        'factories' => [
            'endpointmap' => 'Sds\DoctrineExtensions\Rest\EndpointMapFactory'
        ]
    ];

    /**
     *
     * @var array
     */
    protected $dependencies = array(
        'extension.reference' => true
    );

    protected $endpointMap;

    public function getEndpointMap() {
        return $this->endpointMap;
    }

    public function setEndpointMap($endpointMap) {
        $this->endpointMap = $endpointMap;
    }
}
