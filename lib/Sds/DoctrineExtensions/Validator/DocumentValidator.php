<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Sds\DoctrineExtensions\Accessor\Accessor;
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

        // Check for required fields
        foreach ($metadata->{Sds\Required::metadataKey} as $field => $value){

            if ( ! $value) {
                continue;
            }

            $value = $document->{Accessor::getGetter($metadata, $field, $document)}();
            if ( ! isset($value)) {
                $this->messages = array_merge($this->messages, array(sprintf('Required field %s is not complete', $field)));
                $isValid = false;
            };
        }

        // Property level validators
        if (isset($metadata->{Sds\PropertyValidators::metadataKey})){
            foreach ($metadata->{Sds\PropertyValidators::metadataKey} as $field => $validators){
                $value = $document->{Accessor::getGetter($metadata, $field, $document)}();

                foreach($validators as $class => $options){
                    $validator = new $class($options);
                    if ( ! $validator->isValid($value)){
                        $this->messages = array_merge($this->messages, $validator->getMessages());
                        $isValid = false;
                    }
                }
            }
        }

        // Return early if a property validation has failed
        if ( ! $isValid) {
            return $isValid;
        }

        // Class level validators
        // These are only executed if all property level validators pass
        if (isset($metadata->{Sds\ClassValidators::metadataKey})){
            foreach ($metadata->{Sds\ClassValidators::metadataKey} as $class => $options) {
                $validator = new $class($options);
                if ( ! $validator->isValid($document)){
                    $this->messages = array_merge($this->messages, $validator->getMessages());
                    $isValid = false;
                }
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
