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

    /** Whether or not to re-generate document class if it exists already */
    protected $regenerateIfExists = true;

    protected $documentManager;

    public function getExtension() {
        return $this->extension;
    }

    public function setExtension($extension) {
        $this->extension = $extension;
    }

    public function getRegenerateIfExists() {
        return $this->regenerateIfExists;
    }

    public function setRegenerateIfExists($regenerateIfExists) {
        $this->regenerateIfExists = $regenerateIfExists;
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
     * Generate and write dojo config to disk for the given ClassMetadataInfo instance
     *
     * @param ClassMetadataInfo $metadata
     * @param string $outputDirectory
     * @return void
     */
    public function writeDojo(ClassMetadata $metadata, $outputDirectory) {

        $path = $outputDirectory . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $metadata->name) . $this->extension;
        $dir = dirname($path);

        if ( ! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $isNew = !file_exists($path) || (file_exists($path) && $this->regenerateIfExists);

        // If module doesn't exist or we're re-generating the documents entirely
        if ($isNew) {
            file_put_contents($path, $this->generateDojo($metadata));
        }
    }

    /**
     * Generate a js dojo config from the given ClassMetadataInfo instance
     *
     * @param ClassMetadataInfo $metadata
     * @return string $code
     */
    public function generateDojo(ClassMetadata $metadata) {

        $baseId = str_replace('\\', '/', $metadata->name);

        $templateValues = [];

        list(
            $hasMultiFieldValidator,
            $muliFieldValidator,
            $hasFieldValidator,
            $fieldValidators
        ) = $this->configValidators($metadata);
        
        $serializeList = Serializer::fieldListUp($metadata);
        $className = '';
        if (isset($metadata->serializer['className'])){
            $serializeList[] = '_' . $metadata->serializer['classNameProperty'];
            $className = ",\n                    _" .
                $metadata->serializer['classNameProperty'] .
                ": '" .
                str_replace('\\', '\\\\', $metadata->name) .
                "'";
        }
        $validator = '';
        if ($hasMultiFieldValidator || $hasFieldValidator){
            $validator = ",\n                    _validator: '" . $baseId . "/Validator'";
        }
        $templateValues['model'] = $this->populateTemplate(
            file_get_contents(__DIR__ . '/Template/model.js.template'),
            [
                'id' => $baseId,
                'fields' => str_replace(
                    "\n",
                    "\n                    ",
                    json_encode($serializeList, JSON_PRETTY_PRINT)
                ),
                'className' => $className,
                'validator' => $validator
            ]
        );

        $templateValues['modelValidator'] = '';
        if ($hasMultiFieldValidator || $hasFieldValidator){

            $validators = [];
            $hasMultiFieldValidator ? $validators[] = $baseId . '/MultiFieldValidator' : null;
            $validators = array_merge(
                $validators,
                array_map(
                    function($value) use($baseId) {
                        return $baseId . '/' . ucfirst($value) . '/Validator';
                    },
                    array_keys($fieldValidators)
                )
            );

            $templateValues['modelValidator'] = $this->populateTemplate(
                file_get_contents(__DIR__ . '/Template/modelValidator.js.template'),
                [
                    'id' => $baseId . '/Validator',
                    'validators' => str_replace(
                        "\n",
                        "\n                    ",
                        json_encode($validators, JSON_PRETTY_PRINT)
                    )
                ]
            );
        }

        $templateValues['multiFieldValidator'] = '';
        if ($hasMultiFieldValidator){
            $templateValues['multiFieldValidator'] = $this->populateTemplate(
                file_get_contents(__DIR__ . '/Template/multifieldValidator.js.template'),
                [
                    'id' => $baseId . '/MultiFieldValidator',
                    'config' => str_replace(
                        "\n",
                        "\n            ",
                        json_encode($muliFieldValidator, JSON_PRETTY_PRINT)
                    )
                ]
            );
        }

        $templateValues['fieldValidators'] = '';
        if ($hasFieldValidator){
            $templateValues['fieldValidators'] = '';
            foreach($fieldValidators as $field => $config){
                $templateValues['fieldValidators'] .= $this->populateTemplate(
                    file_get_contents(__DIR__ . '/Template/fieldValidator.js.template'),
                    [
                        'field' => $field,
                        'id' => $baseId . '/' . ucfirst($field) . '/Validator',
                        'config' => str_replace(
                            "\n",
                            "\n            ",
                            json_encode($config, JSON_PRETTY_PRINT)
                         )
                    ]
                );
            }
        }

        if (isset($metadata->dojo['form']) && $metadata->dojo['form']['ignore']){
            $templateValues['form'] = '';
            $templateValues['inputs'] = '';
        } else {
            $formConfig = [];
            $formConfig['base'] = 'Sds/Common/Form/ValidationForm';
            $formConfig['directives'] = ['define' => true, 'declare' => true];
            $formConfig['gets'] = [];
            if ($hasMultiFieldValidator){
                $formConfig['gets']['validator'] = $baseId . '/MultiFieldValidator';
            }
            $formConfig['gets']['inputs'] = [];
            foreach(Serializer::fieldListUp($metadata) as $field){
                $formConfig['gets']['inputs'][] = $baseId . '/' . ucfirst($field) . '/Input';
            }
            $templateValues['form'] = $this->populateTemplate(
                file_get_contents(__DIR__ . '/Template/Form.js.template'),
                [
                    'id' => $baseId . '/Form',
                    'config' => str_replace(
                        "\n",
                        "\n            ",
                        json_encode($formConfig, JSON_PRETTY_PRINT)
                     )
                ]
            );


            //Camel case splitting regex
            $regex = '/# Match position between camelCase "words".
                (?<=[a-z]) # Position is after a lowercase,
                (?=[A-Z]) # and before an uppercase letter.
                /x';

            $templateValues['inputs'] = '';
            foreach(Serializer::fieldListUp($metadata) as $field){
                $inputConfig = [];
                if (isset($fieldValidators[$field])){
                    $inputConfig['base'] = 'Sds/Common/Form/ValidationTextBox';
                    $inputConfig['gets'] = ['validator' => $baseId . '/' . ucfirst($field) . '/validator'];
                } else {
                    $inputConfig['base'] = 'Sds/Common/Form/TextBox';
                }
                if ($metadata->fieldMappings[$field]['type'] == 'boolean'){
                    $inputConfig['base'] = 'Sds/Common/Form/CheckBox';
                }
                $inputConfig['directives'] = ['define' => true, 'declare' => true];

                $inputConfig['params'] = [];
                $inputConfig['params']['name'] = $field;
                $inputConfig['params']['label'] = ucfirst(implode(' ', preg_split($regex, $field))) . ':';

                if ($metadata->fieldMappings[$field]['type'] == 'custom_id'){
                    $inputConfig['params']['type'] = 'hidden';
                }

                if (isset($metadata->dojo['fields'][$field]['input'])){
                    if (isset($metadata->dojo['fields'][$field]['input']['base'])){
                        $inputConfig['base'] = $metadata->dojo['fields'][$field]['input']['base'];
                    }
                    if (isset($metadata->dojo['fields'][$field]['input']['params'])){
                        foreach ($metadata->dojo['fields'][$field]['input']['params'] as $key => $value){
                            $inputConfig['params'][$key] = $value;
                        }
                    }
                    if (isset($metadata->dojo['fields'][$field]['input']['gets'])){
                        foreach ($metadata->dojo['fields'][$field]['input']['gets'] as $key => $value){
                            $inputConfig['gets'][$key] = $value;
                        }
                    }
                }

                $templateValues['inputs'] .= $this->populateTemplate(
                    file_get_contents(__DIR__ . '/Template/Input.js.template'),
                    [
                        'field' => $field,
                        'id' => $baseId . '/' . ucfirst($field) . '/Input',
                        'config' => str_replace(
                            "\n",
                            "\n            ",
                            json_encode($inputConfig, JSON_PRETTY_PRINT)
                         )
                    ]
                );
            }
        }

        return $this->populateTemplate(
            file_get_contents(__DIR__ . '/Template/config.js.template'),
            $templateValues
        );
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
                $multiFieldValidator = ['base' => 'validatorGroup', 'gets' => ['validators' => []]];
                foreach($metadata->validator['document'] as $validator){
                    $base = str_replace('\\', '/', $validator['class']);
                    if(isset($validator['options']) && count($validator['options']) > 0 ){
                        $config = [
                            'base' => $base,
                            'params' => $validator['options']
                        ];
                    } else {
                        $config = $base;
                    }
                    $multiFieldValidator['gets']['validators'][] = $config;
                }
            } else {
                $multiFieldValidator = ['base' => str_replace('\\', '/', $metadata->validator['document']['class'])];
                if (isset($metadata->validator['document']['options'])){
                    $multiFieldValidator['params'] = $metadata->validator['document']['options'];
                }
            }
        }
        if (isset($metadata->dojo['validator'])){
            $hasMultiFieldValidator = true;
            foreach($metadata->dojo['validator'] as $key => $value){
                if (isset($value)){
                    $multiFieldValidator[$key] = $value;
                }
            }
        }

        $hasFieldValidator = false;
        $fieldValidators = [];

        foreach (Serializer::fieldListUp($metadata) as $field){
            if (isset($metadata->validator['fields'][$field])){
                $hasFieldValidator = true;

                if (is_array($metadata->validator['fields'][$field])){
                    $fieldValidators[$field] = ['base' => 'validatorGroup', 'gets' => ['validators' => []]];
                    foreach($metadata->validator['fields'][$field] as $validator){
                        $base = str_replace('\\', '/', $validator['class']);
                        if(isset($validator['options']) && count($validator['options']) > 0 ){
                            $config = [
                                'base' => $base,
                                'params' => $validator['options']
                            ];
                        } else {
                            $config = $base;
                        }
                        $fieldValidators[$field]['gets']['validators'][] = $config;
                    }
                } else {
                    $fieldValidators[$field] = str_replace('\\', '/', ['base' => $metadata->validator['fields'][$field]]);
                    if (isset($metadata->validator['fields'][$field]['options'])){
                        $fieldValidators[$field]['params'] = $metadata->validator['fields'][$field]['options'];
                    }
                }
                $fieldValidators[$field]['params'] = ['field' => $field];
            }
            if (isset($metadata->dojo['fields'][$field]['validator'])){
                $hasFieldValidator = true;
                foreach($metadata->dojo['fields'][$field]['validator'] as $key => $value){
                    if (isset($value)){
                        $fieldValidators[$field][$key] = $value;
                    }
                }
                $fieldValidators[$field]['params'] = ['field' => $field];
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
