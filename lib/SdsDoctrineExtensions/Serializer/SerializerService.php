<?php

namespace SdsDoctrineExtensions\Serializer;

use Doctrine\ODM\MongoDB\DocumentManager,
    SdsDoctrineExtensions\Serializer\Mapping\Driver\Serializer as SerializerDriver;

class SerializerService {

    protected $documentManager;    
    private static $instance;

    private function __construct(){
    }

    public static function getInstance(){
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

    public function __clone(){
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup(){
        trigger_error('Unserializing is not allowed.', E_USER_ERROR);
    }  
    
    public function setDocumentManager(DocumentManager $documentManager){
        $this->documentManager = $documentManager;        
    }
    
    public function serializeArray($document){
        return $this->serialize($document); 
    }
    
    public function serializeJson($document){
        return json_encode($this->serialize($document));
    }
    
    protected function serialize($document){
        $dm = $this->documentManager;
        if(!isset($dm)){
            throw new \Exception('Document Manager must be set before attempting to serialize any documents');
        }
        $metadata = $dm->getMetadataFactory()->getMetadataFor(get_class($document));
        $return = [];
        foreach ($metadata->fieldMappings as $field=>$mapping){
            if(isset($mapping[SerializerDriver::DO_NOT_SERIALIZE]) && 
                $mapping[SerializerDriver::DO_NOT_SERIALIZE]
            ){
                continue;
            }
            if(isset($mapping['embedded'])){
                switch ($mapping['type']){
                    case 'one':
                        $return[$field] = $this->serialize($document->{'get'.ucfirst($field)}());
                        break;
                    case 'many':
                        $return[$field] = [];                
                        $embedDocuments = $document->{'get'.ucfirst($field)}(); 
                        foreach($embedDocuments as $embedDocument){
                            $return[$field][] = $this->serialize($embedDocument);
                        }       
                        break;
                }                
            } else {
                $return[$field] = $document->{'get'.ucfirst($field)}();                
            }
        }
        return $return;
    }
}
