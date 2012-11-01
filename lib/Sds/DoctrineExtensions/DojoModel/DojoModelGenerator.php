<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DojoModel;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;

/**
 * Generate Dojo modules representing Doctrine documents from your mapping information.
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class DojoModelGenerator
{

    /** The extension to use for written js files */
    protected $extension = '.js';

    /** Whether or not to re-generate document class if it exists already */
    protected $regenerateDojoModelIfExists = true;

    protected $documentManager;

    public function getExtension() {
        return $this->extension;
    }

    public function setExtension($extension) {
        $this->extension = $extension;
    }

    public function getRegenerateDojoModelIfExists() {
        return $this->regenerateDojoModelIfExists;
    }

    public function setRegenerateDojoModelIfExists($regenerateDojoModelIfExists) {
        $this->regenerateDojoModelIfExists = $regenerateDojoModelIfExists;
    }

    public function getDocumentManager() {
        return $this->documentManager;
    }

    public function setDocumentManager(DocumentManager $documentManager) {
        $this->documentManager = $documentManager;
    }

    /**
     * Generate and write dojo modules for the given array of ClassMetadataInfo instances
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
                $this->writeDojoModel($metadata, $outputDirectory);
            }
        }
    }

    /**
     * Generate and write dojo module to disk for the given ClassMetadataInfo instance
     *
     * @param ClassMetadataInfo $metadata
     * @param string $outputDirectory
     * @return void
     */
    public function writeDojoModel(ClassMetadata $metadata, $outputDirectory) {

        $path = $outputDirectory . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $metadata->name) . $this->extension;
        $dir = dirname($path);

        if ( ! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $isNew = !file_exists($path) || (file_exists($path) && $this->regenerateDojoModelIfExists);

        // If module doesn't exist or we're re-generating the documents entirely
        if ($isNew) {
            file_put_contents($path, $this->generateDojoModel($metadata));
        }
        
        $dir .= '/Form';
        if ( ! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $path = $dir . '/' . $metadata->name . $this->extension;
        if ($isNew) {
            file_put_contents($path, $this->generateDojoForm($metadata));
        }                
    }

    /**
     * Generate a js dojo module from the given ClassMetadataInfo instance
     *
     * @param ClassMetadataInfo $metadata
     * @return string $code
     */
    public function generateDojoModel(ClassMetadata $metadata) {

        $module = $this->populateModuleTemplate(array(
            'define' => $this->populateDefine($metadata),
            'imports' => $this->populateImports($metadata),
            'moduleName' => str_replace('\\', '/', $metadata->name),
            'moduleDeclare' => str_replace('\\', '/', $metadata->name),
            'inheritFrom' => $this->populateInheritFrom($metadata),
            'documentClass' => $metadata->name,
            'className' => $this->populateClassNameTemplate($metadata),
            'discriminator' => $this->populateDiscriminatorTemplate($metadata),
            'properties' => $this->populatePropertiesTemplate($metadata),
            'jsonFields' => $this->populateJsonFieldsTemplate($metadata)
        ));
        return $module;
    }

    /**
     * Generate a js dojo module from the given ClassMetadataInfo instance
     *
     * @param ClassMetadataInfo $metadata
     * @return string $code
     */
    public function generateDojoForm(ClassMetadata $metadata) {

        $module = $this->populateFormTemplate(array(
            'define' => $this->populateDefine($metadata),
            'imports' => $this->populateImports($metadata),
            'moduleName' => str_replace('\\', '/', $metadata->name),
            'moduleDeclare' => str_replace('\\', '/', $metadata->name),
            'inheritFrom' => $this->populateInheritFrom($metadata),
            'documentClass' => $metadata->name,
            'className' => $this->populateClassNameTemplate($metadata),
            'discriminator' => $this->populateDiscriminatorTemplate($metadata),
            'properties' => $this->populatePropertiesTemplate($metadata),
            'jsonFields' => $this->populateJsonFieldsTemplate($metadata)
        ));
        return $module;
    }
    
    protected function populateDefine(ClassMetadata $metadata){

        $return = '';

        if (isset($metadata->dojo) &&
            isset($metadata->dojo['inheritFrom'])
        ){
            foreach ($metadata->dojo['inheritFrom'] as $module){
                $return .= ",\n        '$module'";
            }
        }
        return $return;
    }

    protected function populateImports(ClassMetadata $metadata){

        $return = '';

        if (isset($metadata->dojo) &&
            isset($metadata->dojo['inheritFrom'])
        ){
            foreach ($metadata->dojo['inheritFrom'] as $module){
                $import = str_replace('/', '', ucfirst($module));
                $return .= ",\n        $import";
            }
        }
        return $return;
    }

    protected function populateInheritFrom(ClassMetadata $metadata){

        $return = '';

        if (isset($metadata->dojo) &&
            isset($metadata->dojo['inheritFrom'])
        ){
            foreach ($metadata->dojo['inheritFrom'] as $module){
                $import = str_replace('/', '', ucfirst($module));
                $return .= ", $import";
            }
        }
        return $return;
    }

    protected function populateModuleTemplate(array $strings) {

        $template = file_get_contents(__DIR__ . '/Template/Module.js.template');
        return $this->populateTemplate($template, $strings);
    }

    protected function populateClassNameTemplate(ClassMetadata $metadata) {

        if ( ! (
            isset($metadata->dojo) &&
            isset($metadata->dojo['className']) &&
            $metadata->dojo['className']
        )) {
            return null;
        }

        $template = file_get_contents(__DIR__ . '/Template/ClassName.js.template');

        $populated = $property = $this->populateTemplate($template, array(
            'name' => $metadata->dojo['classNameProperty'],
            'value' => str_replace('\\', '\\\\', $metadata->name)
        ));

        return $populated;
    }

    protected function populateDiscriminatorTemplate(ClassMetadata $metadata) {

        if ( ! (
               isset($metadata->dojo) &&
               isset($metadata->dojo['discriminator']) &&
               $metadata->dojo['discriminator']
           ) || ! $metadata->hasDiscriminator()
        ) {
            return null;
        }

        $template = file_get_contents(__DIR__ . '/Template/Discriminator.js.template');

        $populated = $property = $this->populateTemplate($template, array(
            'name' => $metadata->discriminatorField['name'],
            'value' => $metadata->discriminatorValue
        ));

        return $populated;
    }

    protected function populatePropertiesTemplate(ClassMetadata $metadata) {

        $template = file_get_contents(__DIR__ . '/Template/Property.js.template');

        $populated = '';

        foreach ($metadata->fieldMappings as $name => $mapping) {

            if (isset($metadata->dojo['fields'][$name]) &&
                isset($metadata->dojo['fields'][$name]['ignore']) &&
                $metadata->dojo['fields'][$name]['ignore']
            ) {
                continue;
            }

            $property = $this->populateTemplate($template, array(
                'name' => $name,
                'type' => $mapping['type']
            ));
            $populated .= $property;
        }

        return $populated;
    }

    protected function populateJsonFieldsTemplate(ClassMetadata $metadata) {

        $template = file_get_contents(__DIR__ . '/Template/jsonFields.js.template');

        $populated = '';

        // Add className
        if (isset($metadata->dojo) &&
            isset($metadata->dojo['className']) &&
            $metadata->dojo['className']
        ) {
            $populated .= $this->populateTemplate($template, array(
                'name' => '_' . $metadata->dojo['classNameProperty']
            ));
        }

        // Add discriminator
        if (isset($metadata->dojo) &&
            isset($metadata->dojo['discriminator']) &&
            $metadata->dojo['discriminator']
        ) {
            $populated .= $this->populateTemplate($template, array(
                'name' =>  '_' . $metadata->discriminatorField['name']
            ));
        }

        // Add fields
        foreach ($metadata->fieldMappings as $name => $mapping) {
            if (isset($metadata->dojo['fields'][$name]) &&
                isset($metadata->dojo['fields'][$name]['ignore']) &&
                $metadata->dojo['fields'][$name]['ignore']
            ) {
                continue;
            }

            $field = $this->populateTemplate($template, array(
                'name' => $name
            ));
            $populated .= $field;
        }

        return $populated;
    }

    protected function populateMetadata(ClassMetadata $metadata) {

        $dojoMetadata = [];

        if (isset($metadata->dojo['validator'])){
            $dojoMetadata['validator'] = $metadata->dojo['validator'];
        }

        if (isset($metadata->dojo['metadata'])){
            $dojoMetadata = array_merge($dojoMetadata, $metadata->dojo['metadata']);
        }

        //Camel case splitting regex
        $regex = '/# Match position between camelCase "words".
            (?<=[a-z])  # Position is after a lowercase,
            (?=[A-Z])   # and before an uppercase letter.
            /x';

        $fields = [];
        foreach ($metadata->fieldMappings as $name => $mapping) {

            if (isset($metadata->dojo['fields'][$name]) &&
                isset($metadata->dojo['fields'][$name]['ignore']) &&
                $metadata->dojo['fields'][$name]['ignore']
            ) {
                continue;
            }

            $attributes = [
                'id' => $name . 'Field',
                'property' => $name,
                'label' => ucfirst(implode(' ', preg_split($regex, $name))) . ':',
                'dataType' => $mapping['type']
            ];

            if (isset($metadata->dojo['fields'][$name]['validator'])){
                $attributes['validator'] = $metadata->dojo['fields'][$name]['validator'];
            }

            if (isset($metadata->dojo['fields'][$name]['metadata'])){
                $attributes = array_merge($attributes, $metadata->dojo['fields'][$name]['metadata']);
            }

            $fields[$name] = $attributes;
        }

        $dojoMetadata['fields'] = $fields;

        return trim(preg_replace('/(.+)/', '        $1', json_encode($dojoMetadata, JSON_PRETTY_PRINT)));
    }

    protected function populateTemplate($template, array $strings) {

        $populated = $template;
        foreach ($strings as $key => $value) {
            $populated = str_replace('<'.$key.'>', $value, $populated);
        }

        return $populated;
    }
}
