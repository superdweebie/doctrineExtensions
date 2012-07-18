<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DojoModel;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;

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
    protected $regenerateDojoModelIfExists = false;

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
            $this->writeDojoModel($metadata, $outputDirectory);
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
            'properties' => $this->populatePropertiesTemplate($metadata->fieldMappings)
        ));
        return $module;
    }

    protected function populateModuleTemplate(array $strings) {

        $template = file_get_contents(__DIR__ . '/Template/Module.js.template');
        return $this->populateTemplate($template, $strings);
    }

    protected function populatePropertiesTemplate(array $fieldMappings) {

        $template = file_get_contents(__DIR__ . '/Template/Property.js.template');

        $populated = '';
        $index = 0;
        $count = count($fieldMappings);

        foreach ($fieldMappings as $name => $mapping) {
            $index++;
            $comma = $index == $count ? '' : ',';
            $property = $this->populateTemplate($template, array(
                'name' => $name,
                'type' => $mapping['type'],
                'comma' => $comma
            ));
            $populated .= $property;
        }

        return $populated;
    }

    protected function populateTemplate($template, array $strings) {

        $populated = $template;
        foreach ($strings as $key => $value) {
            $populated = str_replace('<'.$key.'>', $value, $populated);
        }

        return $populated;
    }
}
