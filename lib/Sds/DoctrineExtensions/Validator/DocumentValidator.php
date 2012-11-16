<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Sds\Common\Validator\ValidatorFactory;
use Sds\Common\Validator\ValidatorResult;
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
        $messages = [];
        $result = true;

        if ( ! isset($metadata->validator)){
            return new ValidatorResult(true, []);
        }

        // Property level validators
        if (isset($metadata->validator['fields'])){
            foreach ($metadata->validator['fields'] as $field => $validatorDefinition){

                $validator = ValidatorFactory::create($validatorDefinition);
                $value = $document->{Accessor::getGetter($metadata, $field, $document)}();

                $validatorResult = $validator->isValid($value);
                if ( ! $validatorResult->getResult()){
                    foreach ($validatorResult->getMessages() as $message){
                        $messages[] = sprintf('Field %s: %s', $field, $message);
                    }
                    $result = false;
                }
            }
        }

        // Class level validators
        if (isset($metadata->validator['document'])){
            $validator = ValidatorFactory::create($metadata->validator['document']);
            $validatorResult = $validator->isValid($value);
            if ( ! $validatorResult->getResult()){
                $messages = array_merge($messages, $validatorResult->getMessages());
                $result = false;
            }
        }

        return new ValidatorResult($result, $messages);
    }
}
