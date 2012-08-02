<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Sds\DoctrineExtensions\AbstractExtensionConfig;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;

/**
 * Defines the resouces this extension requires
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class ExtensionConfig extends AbstractExtensionConfig implements
    AnnotationReaderAwareInterface
{
    use AnnotationReaderAwareTrait;

    /**
     *
     * @var \Sds\Common\Validator\ValidatorInterface
     */
    protected $documentValidator;

    protected $validateOnFlush = true;

    /**
     *
     * @return \Sds\Common\Validator\ValidatorInterface
     */
    public function getDocumentValidator() {
        return $this->documentValidator;
    }

    /**
     *
     * @param \Sds\Common\Validator\ValidatorInterface $validator
     */
    public function setDocumentValidator(DocumentValidatorInterface $documentValidator) {
        $this->documentValidator = $documentValidator;
    }

    public function getValidateOnFlush() {
        return $this->validateOnFlush;
    }

    public function setValidateOnFlush($validateOnFlush) {
        $this->validateOnFlush = (boolean) $validateOnFlush;
    }
}
