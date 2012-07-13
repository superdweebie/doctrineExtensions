<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions;

/**
 * A base class which extensions may extend
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractExtension implements ExtensionInterface {

    /**
     *
     * @var string
     */
    protected $configClass;

    /**
     *
     * @var \SdsDoctrineExtensions\AbstractExtensionConfig
     */
    protected $config;

    /**
     *
     * @var array
     */
    protected $annotations = array();

    /**
     *
     * @var array
     */
    protected $filters = array();

    /**
     *
     * @var array
     */
    protected $subscribers = array();

    /**
     *
     * @var array
     */
    protected $documents = array();

    /**
     *
     * @param \SdsDoctrineExtensions\AbstractExtensionConfig $config
     */
    public function __construct($config = null){
        $configClass = $this->configClass;

        if (is_array($config) ||
            ($config instanceof \Traversable)
        ) {
            $config = new $configClass($config);
        } elseif (!($config instanceof $configClass) && isset($config)) {
            throw new \InvalidArgumentException(sprintf('Argument supplied to Extension constructor must be array, implement Traversable, or instance of %s',
                $configClass));
        }
        $this->config = $config;

        $this->annotations = array('Sds\DoctrineExtensions\Annotation\Annotations' => __DIR__.'/../../');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig(){
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function getAnnotations(){
        return $this->annotations;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(){
        return $this->filters;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribers(){
        return $this->subscribers;
    }

    /**
     * {@inheritdoc}
     */
    public function getDocuments(){
        return $this->documents;
    }
}
