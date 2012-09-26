<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Sds\Common\Validator\ValidatorFactory;
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

    // TODO: make use of a validatorCache - the validator are re-instatated every validation at the moment.
    protected $validatorCache = [];

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

        if ( ! isset($metadata->validator)){
            return true;
        }

        // Property level validators
        if (isset($metadata->validator['fields'])){
            foreach ($metadata->validator['fields'] as $field => $validatorMetadata){

                // Test other validators
                if (isset($validatorMetadata['validatorGroup'])){
                    $validator = ValidatorFactory::createGroup($validatorMetadata['validatorGroup']);
                    $value = $document->{Accessor::getGetter($metadata, $field, $document)}();
                    if ( ! $validator->isValid($value)){
                        $this->messages = array_merge($this->messages, $validator->getMessages());
                        $isValid = false;
                    }
                }
            }
        }

        // Class level validators
        if (isset($metadata->validator['validatorGroup'])){
            $validator = ValidatorFactory::createGroup($metadata->validator['validatorGroup']);
            if ( ! $validator->isValid($document)){
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
