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

        // Property level validators
        foreach ($metadata->validator['fields'] as $field => $validatorMetadata){

            $value = $document->{Accessor::getGetter($metadata, $field, $document)}();

            // Check for required fields
            if (isset($validatorMetadata['required']) &&
                $validatorMetadata['required'] &&
                ! isset($value)
            ) {
                $this->messages = array_merge($this->messages, array(sprintf('Required field %s is not complete', $field)));
                $isValid = false;
            };

            // Test other validators
            if (isset($validatorMetadata['validatorGroup'])){
                foreach ($validatorMetadata['validatorGroup'] as $class => $options){
                    $validator = new $class($options);
                    if ( ! $validator->isValid($value)){
                        $this->messages = array_merge($this->messages, $validator->getMessages());
                        $isValid = false;
                    }
                }
            }
        }

        // Class level validators
        if (isset($metadata->validator['validatorGroup'])){
            foreach ($metadata->validator['validatorGroup'] as $class => $options){
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
