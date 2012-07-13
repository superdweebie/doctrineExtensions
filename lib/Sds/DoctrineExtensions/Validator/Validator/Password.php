<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Traversable;
use Zend\Validator\Regex;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Validator extends Regex
{

    /**
     * Sets validator options
     * TODO: this doesn't validate a strong password - needs to be changed
     *
     * @param  string|Traversable $pattern
     * @throws Exception\InvalidArgumentException On missing 'pattern' parameter
     */
    public function __construct($pattern)
    {
        $pattern = "/^[a-zA-Z][a-zA-Z0-9_-]{1,49}/";
        parent::__construct($pattern);
    }
}
