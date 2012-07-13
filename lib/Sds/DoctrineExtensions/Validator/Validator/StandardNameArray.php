<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Traversable;
use Zend\Validator\AbstractValidator;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class StandardNameArray extends AbstractValidator
{

    public function isValid($names)
    {
        $validator = new StandardName();
        $isValid = true;

        foreach ($names as $name) {
            if (!$validator->isValid($name)) {
                $isValid = false;
            }
        }

        return $isValid;
    }
}
