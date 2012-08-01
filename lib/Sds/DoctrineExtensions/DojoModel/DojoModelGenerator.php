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
    }

    /**
     * Generate a js dojo module from the given ClassMetadataInfo instance
     *
     * @param ClassMetadataInfo $metadata
     * @return string $code
     */
    public function generateDojoModel(ClassMetadata $metadata) {

        $module = $this->populateModuleTemplate(array(
            'moduleName' => str_replace('\\', '/', $metadata->name),
            'moduleDeclare' => str_replace('\\', '.', $metadata->name),
            'documentClass' => $metadata->name,
            'className' => $this->populateClassNameTemplate($metadata),
            'discriminator' => $this->populateDiscriminatorTemplate($metadata),
            'properties' => $this->populatePropertiesTemplate($metadata->fieldMappings),
            'jsonFields' => $this->populateJsonFieldsTemplate($metadata)
        ));
        return $module;
    }

    protected function getInheritedMetadataValue(ClassMetadata $metadata, $key){
        if (isset($metadata->$key)) {
            return $metadata->$key;
        } else {
            if (count($metadata->parentClasses) > 0) {
                foreach ($metadata->parentClasses as $parentClass) {
                    $return = $this->getInheritedMetadataValue(
                        $this->documentManager->getClassMetadata($parentClass),
                        $key
                    );
                    if ($return) {
                        break;
                    };
                }
                return $return;
            } else {
                return null;
            }
        }
    }

    protected function populateModuleTemplate(array $strings) {

        $template = file_get_contents(__DIR__ . '/Template/Module.js.template');
        return $this->populateTemplate($template, $strings);
    }

    protected function populateClassNameTemplate(ClassMetadata $metadata) {

        $name = $this->getInheritedMetadataValue($metadata, Sds\DojoClassName::metadataKey);
        if (! isset($name)) {
            return null;
        }

        $template = file_get_contents(__DIR__ . '/Template/ClassName.js.template');

        $populated = $property = $this->populateTemplate($template, array(
            'name' => $name,
            'value' => str_replace('\\', '\\\\', $metadata->name)
        ));

        return $populated;
    }

    protected function populateDiscriminatorTemplate(ClassMetadata $metadata) {

        $discriminator = $this->getInheritedMetadataValue($metadata, Sds\DojoDiscriminator::metadataKey);
        if ((! isset($discriminator)) ||
            (! $metadata->hasDiscriminator())
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

    protected function populatePropertiesTemplate(array $fieldMappings) {

        $template = file_get_contents(__DIR__ . '/Template/Property.js.template');

        $populated = '';

        foreach ($fieldMappings as $name => $mapping) {
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
        $name = $this->getInheritedMetadataValue($metadata, Sds\DojoClassName::metadataKey);
        if (isset($name)) {
            $populated .= $this->populateTemplate($template, array(
                'name' => '_' . $name
            ));
        }

        // Add discriminator
        $discriminator = $this->getInheritedMetadataValue($metadata, Sds\DojoDiscriminator::metadataKey);
        if (isset($discriminator)) {
            $populated .= $this->populateTemplate($template, array(
                'name' =>  '_' . $metadata->discriminatorField['name']
            ));
        }
        
        // Add fields
        foreach ($metadata->fieldMappings as $name => $mapping) {
            $field = $this->populateTemplate($template, array(
                'name' => $name
            ));
            $populated .= $field;
        }

        return  substr($populated, 0, -2);
    }
    
    protected function populateTemplate($template, array $strings) {

        $populated = $template;
        foreach ($strings as $key => $value) {
            $populated = str_replace('<'.$key.'>', $value, $populated);
        }

        return $populated;
    }
}
