<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

use Sds\DoctrineExtensions\Exception;
use Zend\Stdlib\ArrayUtils;

/**
 * A base class which extensions configs must extend
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractExtension {

    protected $documents = [];

    protected $filters = [];

    protected $subscribers = [];

    protected $cliCommands = [];

    protected $cliHelpers = [];

    protected $defaultServiceManagerConfig = [];

    protected $serviceManagerConfig = [];

    /**
     * List of other extensions which must be loaded
     * for this extension to work
     *
     * @var array
     */
    protected $dependencies = [];

    public function getServiceManagerConfig() {
        return ArrayUtils::merge($this->defaultServiceManagerConfig, $this->serviceManagerConfig);
    }

    public function setServiceManagerConfig($serviceManagerConfig) {
        $this->serviceManagerConfig = $serviceManagerConfig;
    }

    public function getDocuments() {
        return $this->documents;
    }

    public function setDocuments($documents) {
        $this->documents = $documents;
    }

    public function getFilters() {
        return $this->filters;
    }

    public function setFilters($filters) {
        $this->filters = $filters;
    }

    public function getSubscribers() {
        return $this->subscribers;
    }

    public function setSubscribers($subscribers) {
        $this->subscribers = $subscribers;
    }

    public function getCliCommands() {
        return $this->cliCommands;
    }

    public function setCliCommands($cliCommands) {
        $this->cliCommands = $cliCommands;
    }

    public function getCliHelpers() {
        return $this->cliHelpers;
    }

    public function setCliHelpers($cliHelpers) {
        $this->cliHelpers = $cliHelpers;
    }

    /**
     *
     * @return array
     */
    public function getDependencies() {
        return $this->dependencies;
    }

    /**
     *
     * @param array $dependencies
     */
    public function setDependencies(array $dependencies) {
        $this->dependencies = $dependencies;
    }

    /**
     * @param  array|Traversable|null $options
     * @return AbstractOptions
     * @throws Exception\InvalidArgumentException
     */
    public function __construct(array $options = null)
    {
        if (null !== $options) {
            $this->setFromArray($options);
        }
    }

    /**
     * @param  array|Traversable $options
     * @return void
     */
    public function setFromArray($options)
    {
        if (!is_array($options) && !$options instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Options provided to %s must be an array or Traversable',
                __METHOD__
            ));
        }

        foreach ($options as $key => $value) {
            $this->__set($key, $value);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $setter = 'set' . ucfirst($key);
        if (method_exists($this, $setter)){
            $this->{$setter}($value);
        }
    }
}
