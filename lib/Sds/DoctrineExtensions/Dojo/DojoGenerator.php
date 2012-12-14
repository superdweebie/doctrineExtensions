<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Dojo;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Sds\DoctrineExtensions\Serializer\Serializer;

/**
 * Generate Dojo config representing Doctrine documents from mapping information.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class DojoGenerator
{

    /** The extension to use for written js files */
    protected $extension = '.js';

    protected $documentManager;

    public function getExtension() {
        return $this->extension;
    }

    public function setExtension($extension) {
        $this->extension = $extension;
    }

    public function getDocumentManager() {
        return $this->documentManager;
    }

    public function setDocumentManager(DocumentManager $documentManager) {
        $this->documentManager = $documentManager;
    }

    /**
     * Generate and write dojo config for the given array of ClassMetadataInfo instances
     *
     * @param array $metadatas
     * @param string $outputDirectory
     * @return void
     */
    public function generate(array $metadatas, $outputDirectory)
    {
        foreach ($metadatas as $metadata) {
            if (! $metadata->isMappedSuperclass &&
                ! $metadata->reflClass->isAbstract()
            ) {
                $this->writeDojo($metadata, $outputDirectory);
            }
        }
    }

    /**
     * Generate and write dojo modules to disk for the given ClassMetadataInfo instance
     *
     * @param ClassMetadataInfo $metadata
     * @param string $outputDirectory
     * @return void
     */
    public function writeDojo(ClassMetadata $metadata, $outputDirectory) {

        foreach ($this->generateDojo($metadata) as $fileName => $content){
            $path = $outputDirectory . '/' . $fileName;
            $dir = dirname($path);

            if ( ! is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            file_put_contents($path, $content);
        }
    }

    /**
     * Generate a js dojo config from the given ClassMetadataInfo instance
     *
     * @param ClassMetadataInfo $metadata
     * @return string $code
     */
    public function generateDojo(ClassMetadata $metadata) {

        $return = [];
        $basePath = str_replace('\\', DIRECTORY_SEPARATOR, $metadata->name);
        $baseId = str_replace('\\', '/', $metadata->name);

        list(
            $hasMultiFieldValidator,
            $muliFieldValidator,
            $hasFieldValidator,
            $fieldValidators
        ) = $this->configValidators($metadata);


        //Generate Model module
        $serializeList = Serializer::fieldListForUnserialize($metadata);
        $className = '';
        if (isset($metadata->serializer['className'])){
            $serializeList[] = $metadata->serializer['classNameProperty'];
            $className = ",\n            " .
                $metadata->serializer['classNameProperty'] .
                ": '" .
                str_replace('\\', '\\\\', $metadata->name) .
                "'";
        }
        $validator = '';
        if ($hasMultiFieldValidator || $hasFieldValidator){
            $validator = ",\n            _validatorMid: '" . $baseId . "/ModelValidator'";
        }
        $return[$basePath . $this->extension] = $this->populateTemplate(
            file_get_contents(__DIR__ . '/Template/Model.js.template'),
            [
                'mid' => $baseId,
                'fields' => str_replace(
                    "\n",
                    "\n            ",
                    json_encode($serializeList, JSON_PRETTY_PRINT)
                ),
                'className' => $className,
                'validatorMid' => $validator
            ]
        );


        //Generate ModelValidator
        if ($hasMultiFieldValidator || $hasFieldValidator){

            $validatorMids = [];
            $validators = [];
            $params = ['validators' => []];
            if ($hasMultiFieldValidator) {
                $validatorMids[] = $baseId . '/MultiFieldValidator';
                $validators[] = 'MultiFieldValidator';
                $params['validators'][] = 'new MultiFieldValidator';
            }
            foreach ($fieldValidators as $field => $config){
                $validatorMids[] = $baseId . '/' . ucfirst($field) . '/Validator';
                $validators[] = ucfirst($field) . 'Validator';
                $params['validators'][] = 'new ' . ucfirst($field) . 'Validator';
            }

            $return[$basePath . '/ModelValidator' . $this->extension] = $this->populateTemplate(
                file_get_contents(__DIR__ . '/Template/ModelValidator.js.template'),
                [
                    'mid' => $baseId . '/ModelValidator',
                    'validatorMids' => ",\n    '" . implode("',\n    '", $validatorMids) . "'",
                    'validators' => ",\n    " . implode(",\n    ", $validators),
                    'params' => "validators: [\n                " . implode(",\n                ", $params['validators']) . "\n            ]"
                ]
            );
        }

        if ($hasMultiFieldValidator){
            $muliFieldValidator['params']['validators'] = array_map(
                function($value){return str_replace("\n", "\n                ", $value);},
                $muliFieldValidator['params']['validators']
            );
            $return[$basePath . '/MultiFieldValidator' . $this->extension] = $this->populateTemplate(
                file_get_contents(__DIR__ . '/Template/MultifieldValidator.js.template'),
                [
                    'baseMid' => $muliFieldValidator['baseMid'],
                    'validatorMids' => ",\n    '" . implode("',\n    '", $muliFieldValidator['validatorMids']) . "'",
                    'base' => $muliFieldValidator['base'],
                    'mid' => $baseId . '/MultiFieldValidator',
                    'validators' => ",\n    " . implode(",\n    ", $muliFieldValidator['validators']),
                    'params' => "validators: [\n                " . implode(",\n                ", $muliFieldValidator['params']['validators']) . "\n            ]"
                ]
            );
        }

        //Generate validator for each field
        if ($hasFieldValidator){
            foreach($fieldValidators as $field => $config){
                $params = [];
                foreach ($config['params'] as $key => $value){
                    if ($key == 'validators'){
                        $params[] = "validators: [\n                " . implode(",\n                ", str_replace("\n", "\n                ", $value)) . "\n            ]";
                    } else {
                        $params[] = "$key: " . json_encode($value, JSON_PRETTY_PRINT);
                    }
                }
                $params = implode(",\n\n            ", $params);

                if(isset($config['validatorMids'])){
                    $validatorMids = ",\n    '" . implode("',\n    '", $config['validatorMids']) . "'";
                } else {
                    $validatorMids = '';
                }
                if(isset($config['validators'])){
                    $validators = ",\n    " . implode(",\n    ", $config['validators']);
                } else {
                    $validators = '';
                }

                $return[$basePath . '/' . ucfirst($field) . '/Validator' . $this->extension]= $this->populateTemplate(
                    file_get_contents(__DIR__ . '/Template/FieldValidator.js.template'),
                    [
                        'baseMid' => $config['baseMid'],
                        'validatorMids' => $validatorMids,
                        'base' => $config['base'],
                        'field' => $field,
                        'mid' => $baseId . '/' . ucfirst($field) . '/Validator',
                        'validators' => $validators,
                        'params' => $params
                    ]
                );
            }
        }

        //Generate form
        if ($hasMultiFieldValidator){
            $validatorBase = ",\n    '$baseId/MultiFieldValidator'";
            $validator = ",\n    MultiFieldValidator";
            $validatorParam = "validator: new MultiFieldValidator,";
        } else {
            $validatorBase = null;
            $validator = null;
            $validatorParam = null;
        }
        $inputBases = [];
        $inputs = [];
        $inputParams = [];
        foreach(Serializer::fieldListForUnserialize($metadata) as $field){
            $inputBases[] = "'" . $baseId . '/' . ucfirst($field) . '/Input' . "'";
            $inputs[] = ucfirst($field) . 'Input';
            $inputParams[] = 'new ' . ucfirst($field) . 'Input';
        }
        $return[$basePath . '/Form' . $this->extension] = $this->populateTemplate(
            file_get_contents(__DIR__ . '/Template/Form.js.template'),
            [
                'validatorBase' => $validatorBase,
                'inputBases' => ",\n    " . implode(",\n    ", $inputBases),
                'mid' => $baseId . '/Form',
                'validator' => $validator,
                'inputs' => count($inputs) > 0 ? ",\n    " . implode(",\n    ", $inputs) : '',
                'validatorParam' => $validatorParam,
                'inputParams' => "[\n                " . implode(",\n                ", $inputParams) . "\n            ]"
            ]
        );


        //Camel case splitting regex
        $regex = '/# Match position between camelCase "words".
            (?<=[a-z]) # Position is after a lowercase,
            (?=[A-Z]) # and before an uppercase letter.
            /x';

        //Generate inputs
        foreach(Serializer::fieldListForUnserialize($metadata) as $field){

            $params = [];
            if (isset($fieldValidators[$field])){
                $baseMid = 'Sds/Common/Form/ValidationTextBox';
                $base = 'ValidationTextBox';
                $params['validator'] = 'new ' . ucfirst($field) . 'Validator';
                $validatorBase = ",\n    '" . $baseId . '/' . ucfirst($field) . '/Validator' . "'";
                $validator = ",\n    " . ucfirst($field) . 'Validator';
            } else {
                $baseMid = 'Sds/Common/Form/TextBox';
                $base = 'TextBox';
                $validatorBase = null;
                $validator = null;
            }
            if ($metadata->fieldMappings[$field]['type'] == 'boolean'){
                $baseMid = 'Sds/Common/Form/CheckBox';
                $base = 'CheckBox';
            }

            $params['name'] = $field;
            $params['label'] = ucfirst(implode(' ', preg_split($regex, $field))) . ':';

            if ($metadata->fieldMappings[$field]['type'] == 'custom_id'){
                $params['type'] = 'hidden';
            }

            if (isset($metadata->dojo['fields'][$field]['input'])){
                if (isset($metadata->dojo['fields'][$field]['input']['base'])){
                    $baseMid = $metadata->dojo['fields'][$field]['input']['base'];
                    $pieces = explode('/', $baseMid);
                    $base = $pieces[count($pieces) - 1];
                }
                if (isset($metadata->dojo['fields'][$field]['input']['params'])){
                    foreach ($metadata->dojo['fields'][$field]['input']['params'] as $key => $value){
                        $params[$key] = $value;
                    }
                }
            }

            $renderParams = [];
            foreach ($params as $key => $value){
                if ($key == 'validator'){
                    $renderParams[] = "validator: $value";
                } else {
                    $renderParams[] = "$key: " . json_encode($value, JSON_PRETTY_PRINT);
                }
            }
            $renderParams = implode(",\n\n            ", $renderParams);

            $return[$basePath . '/' . ucfirst($field) . '/Input' . $this->extension] = $this->populateTemplate(
                file_get_contents(__DIR__ . '/Template/Input.js.template'),
                [
                    'field' => $field,
                    'mid' => $baseId . '/' . ucfirst($field) . '/Input',
                    'baseMid' => $baseMid,
                    'validatorBase' => $validatorBase,
                    'validator' => $validator,
                    'base' => $base,
                    'params' => $renderParams
                ]
            );
        }

        //Generate store
        $pieces = explode('/', $baseId);
        $model = $pieces[count($pieces) - 1];
        $return[$basePath . '/JsonRestStore' . $this->extension] = $this->populateTemplate(
            file_get_contents(__DIR__ . '/Template/JsonRestStore.js.template'),
            [
                'modelMid' => $baseId,
                'model' =>  $model,
                'mid' => $baseId . '/JsonRestStore',
                'name' => $metadata->collection,
                'target' => $metadata->rest['url'],
                'idProperty' => $metadata->identifier
            ]
        );

        return $return;
    }

    protected function populateTemplate($template, array $strings) {

        $populated = $template;
        foreach ($strings as $key => $value) {
            $populated = str_replace('<'.$key.'>', $value, $populated);
        }
        return $populated;
    }

    protected function configValidators(ClassMetadata $metadata){

        $hasMultiFieldValidator = false;
        $multiFieldValidator = [];

        if (isset($metadata->validator['document'])){
            $hasMultiFieldValidator = true;
            if (is_array($metadata->validator['document'])){
                $multiFieldValidator = [
                    'baseMid' => 'Sds/Common/Validator/ValidatorGroup',
                    'base' => 'ValidatorGroup',
                    'validatorMids' => [],
                    'validators' => [],
                    'params' => []
                ];
                foreach($metadata->validator['document'] as $validator){
                    $validatorMid = str_replace('\\', '/', $validator['class']);
                    $multiFieldValidator['validatorMids'][] = $validatorMid;
                    $pieces = explode('/', $validatorMid);
                    $validatorName = $pieces[count($pieces) - 1];
                    $multiFieldValidator['validators'][] = $validatorName;
                    if(isset($validator['options']) && count($validator['options']) > 0 ){
                        $params = json_encode($validator['options'], JSON_PRETTY_PRINT);
                        $multiFieldValidator['params']['validators'][] = "new $validatorName($params)";
                    } else {
                        $multiFieldValidator['params']['validators'][] = "new $validatorName";
                    }
                }
            } else {
                $multiFieldValidator['baseMid'] = str_replace('\\', '/', $metadata->validator['document']['class']);
                $pieces = explode('/', $multiFieldValidator['baseMid']);
                $multiFieldValidator['base'] = $pieces[count($pieces) - 1];
                if (isset($metadata->validator['document']['options'])){
                    $multiFieldValidator['params'] = $metadata->validator['document']['options'];
                }
            }
        }

        $hasFieldValidator = false;
        $fieldValidators = [];

        foreach (Serializer::fieldListForUnserialize($metadata) as $field){
            if (isset($metadata->validator['fields'][$field])){
                $hasFieldValidator = true;

                if (count($metadata->validator['fields'][$field]) > 1){
                    $fieldValidators[$field] = [
                        'baseMid' => 'Sds/Common/Validator/ValidatorGroup',
                        'base' => 'ValidatorGroup',
                        'validatorMids' => [],
                        'validators' => [],
                        'params' => ['field' => $field]
                    ];

                    foreach($metadata->validator['fields'][$field] as $validator){
                        $validatorMid = str_replace('\\', '/', $validator['class']);
                        $pieces = explode('/', $validatorMid);
                        $validatorName = $pieces[count($pieces) - 1];
                        $fieldValidators[$field]['validatorMids'][] = $validatorMid;
                        $fieldValidators[$field]['validators'][] = $validatorName;

                        if(isset($validator['options']) && count($validator['options']) > 0 ){
                            $params = json_encode($validator['options'], JSON_PRETTY_PRINT);
                            $fieldValidators[$field]['params']['validators'][] = "new $validatorName($params)";
                        } else {
                            $fieldValidators[$field]['params']['validators'][] = "new $validatorName";
                        }
                    }
                } else {
                    $validator = $metadata->validator['fields'][$field][0];
                    $baseMid = str_replace('\\', '/', $validator['class']);
                    $pieces = explode('/', $baseMid);
                    $base = $pieces[count($pieces) - 1];
                    if (isset($validator['options'])){
                        $params = array_merge(['field' => $field], $validator['options']);
                    } else {
                        $params = ['field' => $field];
                    }
                    $fieldValidators[$field] = [
                        'baseMid' => $baseMid,
                        'base' => $base,
                        'params' => $params
                    ];
                }
            }
        }

        return [
            $hasMultiFieldValidator,
            $multiFieldValidator,
            $hasFieldValidator,
            $fieldValidators
        ];
    }
}
