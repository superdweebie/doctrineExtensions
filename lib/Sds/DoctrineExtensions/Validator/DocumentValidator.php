<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Sds\DoctrineExtensions\DocumentManagerAwareInterface;
use Sds\DoctrineExtensions\DocumentManagerAwareTrait;
use Sds\Validator\Factory as ValidatorFactory;
use Sds\Validator\ValidatorResult;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class DocumentValidator implements DocumentValidatorInterface, DocumentManagerAwareInterface
{

    use DocumentManagerAwareTrait;

    /**
     *
     * @param object $document
     * @return boolean
     */
    public function isValid($document) {
        $messages = [];
        $result = true;

        $metadata = $this->documentManager->getClassMetadata(get_class($document));

        if ( ! isset($metadata->validator)){
            return new ValidatorResult(true, []);
        }

        // Field level validators
        if (isset($metadata->validator['fields'])){
            foreach ($metadata->validator['fields'] as $field => $validatorDefinition){

                //check for hashed or encrypted values - if the field has been persisted, and is unchanged,
                //it is assumed to be vaild. If the encrypted value is passed to the validators, then
                //it is likely fail, which isn't correct.
                if ((isset($metadata->crypt['hash'][$field]) ||
                    isset($metadata->crypt['blockCipher'][$field]))
                ) {
                    $originalDocumentData = $this->documentManager->getUnitOfWork()->getOriginalDocumentData($document);
                    if (isset($originalDocumentData) &&
                        isset($originalDocumentData[$field]) &&
                        $originalDocumentData[$field] == $metadata->reflFields[$field]->getValue($document)
                    ){
                        //encrypted value hasn't changed, so skip validation of it.
                        continue;
                    }
                }

                $validator = ValidatorFactory::create($validatorDefinition);
                $value = $metadata->reflFields[$field]->getValue($document);

                $validatorResult = $validator->isValid($value);
                if ( ! $validatorResult->getResult()){
                    $messages[$field] = $validatorResult->getMessages();
                    $result = false;
                }
            }
        }

        // Document level validators
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
