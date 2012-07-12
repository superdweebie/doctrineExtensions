<?php
/**
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Validator;

use Doctrine\ODM\MongoDB\DocumentManager;
use Sds\DoctrineExtensions\Accessor\MetadataInjector as AccessorInjector;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class DocumentValidator implements DocumentValidatorInterface
{

    protected $documentManager;

    protected $messages = array();

    /**
     *
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getDocumentManager() {
        return $this->documentManager;
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\DocumentManager $documentManager
     */
    public function setDocumentManager(DocumentManager $documentManager) {
        $this->documentManager = $documentManager;
    }

    /**
     * Using zend\form\annotation\validator annotations, this method will check if a document
     * is valid.
     *
     * @param object $document
     * @return boolean
     */
    public function isValid($document) {
        $this->messages = array();
        $isValid = true;

        $metadata = $this->documentManager->getClassMetadata(get_class($document));

        if (!isset($metadata->requiresValidation)) {
            return $isValid;
        }

        // Property level validators
        foreach ($metadata->fieldMappings as $field=>$mapping){

            if(isset($metadata[$field][AccessorInjector::getter])
            ){
                $getMethod = $metadata[$field][AccessorInjector::getter];
            } else {
                $getMethod = 'get'.ucfirst($field);
            }
            $value = $document->$getMethod();

            foreach ($mapping[MetadataInjector::validator] as $class => $options) {
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
        foreach ($metadata->{MetadataInjector::validator} as $class => $options) {
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
