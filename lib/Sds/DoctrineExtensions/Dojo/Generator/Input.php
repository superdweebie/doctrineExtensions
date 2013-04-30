<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Dojo\Generator;

use Sds\DoctrineExtensions\Generator\GenerateEventArgs;
use Zend\Json\Expr;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Input extends AbstractDojoGenerator
{

    const event = 'generatorDojoInput';

    public static function getStaticSubscribedEvents(){
        return [
            self::event,
        ];
    }

    public function getFilePath($className, $fieldName = null){
        return parent::getFilePath($className) . '/' . ucfirst($fieldName) . '/Input.js';
    }

    static public function getResourceName($className, $fieldName = null){
        return parent::getResourceName($className) . '/' . ucfirst($fieldName) . '/Input.js';
    }

    static public function getMid($className, $fieldName = null){
        return parent::getMid($className) . '/' . ucfirst($fieldName) . '/Input';
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Generator\GenerateEventArgs $eventArgs
     */
    public function generatorDojoInput(GenerateEventArgs $eventArgs)
    {

        $metadata = $eventArgs->getDocumentManager()->getClassMetadata($eventArgs->getClassName());
        $options = $eventArgs->getOptions();
        $resource = $eventArgs->getResource();
        $field = $options['field'];
        $defaultMixins = $this->getDefaultMixins();

        $templateArgs = [];

        $hasValidator = array_key_exists(Validator::getResourceName($metadata->name, $field), $metadata->generator);

        $params = [];

        if ($hasValidator){
            switch ($metadata->fieldMappings[$field]['type']){
                case 'int':
                    $defaultMids = $defaultMixins['input']['intWithValidator'];
                    break;
                case 'float':
                    $defaultMids = $defaultMixins['input']['floatWithValidator'];
                    break;
                case 'custom_id':
                    $params['type'] = "hidden";
                case 'string':
                default:
                    $defaultMids = $defaultMixins['input']['stringWithValidator'];
                    break;
            }
            if (isset($options['mixins'])){
                $templateArgs['dependencyMids'] = $options['mixins'];
            } else {
                $templateArgs['dependencyMids'] = $defaultMids;
            }
            $templateArgs['dependencies'] = $this->namesFromMids($templateArgs['dependencyMids']);
            $templateArgs['mixins'] = $this->namesFromMids($templateArgs['dependencyMids']);

            $templateArgs['dependencyMids'][] = Validator::getMid($metadata->name, $field);
            $templateArgs['dependencies'][] = ucfirst($field) . 'Validator';
            $params['validator'] = new Expr('new ' . ucfirst($field) . 'Validator');
        } else {
            switch ($metadata->fieldMappings[$field]['type']){
                case 'int':
                    $defaultMids = $defaultMixins['input']['int'];
                    break;
                case 'float':
                    $defaultMids = $defaultMixins['input']['float'];
                    break;
                case 'custom_id':
                    $params['type'] = 'hidden';
                case 'string':
                default:
                    $defaultMids = $defaultMixins['input']['string'];
                    break;
            }

            if (isset($options['mixins'])){
                $templateArgs['dependencyMids'] = $options['mixins'];
            } else {
                $templateArgs['dependencyMids'] = $defaultMids;
            }

            $templateArgs['dependencies'] = $this->namesFromMids($templateArgs['dependencyMids']);
            $templateArgs['mixins'] = $this->namesFromMids($templateArgs['dependencyMids']);
        }

        $params['name'] = $field;

        //Camel case splitting regex
        $regex = '/(?<=[a-z])(?=[A-Z])/x';

        $params['label'] = ucfirst(implode(' ', preg_split($regex, $field)));

        if (isset($options['params'])){
            foreach ($options['params'] as $key => $value){
                $params[$key] = $value;
            }
        }

        $templateArgs['dependencyMids'] = ',' . $this->indent($this->implodeMids($templateArgs['dependencyMids']));
        $templateArgs['dependencies'] = ',' . $this->indent($this->implodeNames($templateArgs['dependencies']));
        $templateArgs['mixins'] = $this->indent($this->implodeNames($templateArgs['mixins']), 12) . $this->indent("\n", 8);
        $templateArgs['params'] = $this->implodeParams($params);
        $templateArgs['comment'] = $this->indent("// Will return an input for the $field field");

        $resource->content = $this->populateTemplate(
            file_get_contents(__DIR__ . '/Template/Module.js.template'),
            $templateArgs
        );

        $this->persistToFile($this->getFilePath($metadata->name, $field), $resource->content);

    }
}
