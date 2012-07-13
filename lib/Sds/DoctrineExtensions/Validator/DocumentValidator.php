<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class DocumentValidator implements DocumentValidatorInterface
{

    protected $messages = array();

    /**
     * Using zend\form\annotation\validator annotations, this method will check if a document
     * is valid.
     *
     * @param object $document
     * @return boolean
     */
    public function isValid($document, ClassMetadata $metadata) {
        $this->messages = array();
        $isValid = true;

        if (!isset($metadata->requiresValidation)) {
            return $isValid;
        }

        // Property level validators
        foreach ($metadata->fieldMappings as $field=>$mapping){

            if (!isset($mapping[Sds\Validator::metadataKey])) {
                continue;
            }

            if(isset($mapping[Sds\Getter::metadataKey])
            ){
                $getMethod = $mapping[Sds\Getter::metadataKey];
            } else {
                $getMethod = 'get'.ucfirst($field);
            }
            $value = $document->$getMethod();

            foreach ($mapping[Sds\Validator::metadataKey] as $class => $options) {
                $validator = new $class($options);
                if (!$validator->isValid($value)){
                    $this->messages = array_merge($this->messages, $validator->getMessages());
                    $isValid = false;
                }
            }
        }

        // Return early if a property validation has failed
        if (!$isValid) {
            return $isValid;
        }

        // Class level validators
        // These are only executed if all property level validators pass
        if (!isset($metadata->{Sds\Validator::metadataKey})){
            return $isValid;
        }

        foreach ($metadata->{Sds\Validator::metadataKey} as $class => $options) {
            $validator = new $class($options);
            if (!$validator->isValid($value)){
                $this->messages = array_merge($this->messages, $validator->getMessages());
                $isValid = false;
            }
        }

        return $isValid;
    }

    /**
     *
     * {@inheritdoc}
     */
    public function getMessages() {
        return $this->messages;
    }
}
