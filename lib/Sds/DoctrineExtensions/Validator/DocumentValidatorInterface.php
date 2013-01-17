<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
interface DocumentValidatorInterface
{

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  document $value
     * @return Sds\Validator\ValidatorResult
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value, ClassMetadata $metadata);

    public function setDocumentManager($documentManager);
}
