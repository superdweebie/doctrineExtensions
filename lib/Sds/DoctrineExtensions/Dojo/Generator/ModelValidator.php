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
class ModelValidator extends AbstractDojoGenerator
{

    const event = 'generatorDojoModelValidator';

    public static function getStaticSubscribedEvents(){
        return [
            self::event,
        ];
    }

    public function getFilePath($className, $fieldName = null){
        return parent::getFilePath($className) . '/ModelValidator.js';
    }

    static public function getResourceName($className, $fieldName = null){
        return parent::getResourceName($className) . '/ModelValidator.js';
    }

    static public function getMid($className, $fieldName = null){
        return parent::getMid($className) . '/ModelValidator';
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Generator\GenerateEventArgs $eventArgs
     */
    public function generatorDojoModelValidator(GenerateEventArgs $eventArgs)
    {

        $metadata = $eventArgs->getDocumentManager()->getClassMetadata($eventArgs->getClassName());
        $options = $eventArgs->getOptions();
        $resource = $eventArgs->getResource();
        $defaultMixins = $this->getDefaultMixins();

        $templateArgs = [];

        $hasMultiFieldValidator = array_key_exists(MultiFieldValidator::getResourceName($metadata->name), $metadata->generator);

        $params = ['validators' => []];

        if (isset($options['mixins'])){
            $templateArgs['dependencyMids'] = $options['mixins'];
        } else {
            $templateArgs['dependencyMids'] = $defaultMixins['validator']['model'];
        }

        $templateArgs['mixins'] = $this->namesFromMids($templateArgs['dependencyMids']);
        $templateArgs['dependencies'] = $this->namesFromMids($templateArgs['dependencyMids']);
        if ($hasMultiFieldValidator){
            $templateArgs['dependencyMids'][] = MultiFieldValidator::getMid($metadata->name);
            $templateArgs['dependencies'][] = 'MultiFieldValidator';
            $params['validators'][] = new Expr('new MultiFieldValidator');
        }
        foreach($this->getSerializer()->fieldListForUnserialize($metadata) as $field){
            if (array_key_exists(Validator::getResourceName($metadata->name, $field), $metadata->generator)){
                $templateArgs['dependencyMids'][] = Validator::getMid($metadata->name, $field);
                $templateArgs['dependencies'][] = ucfirst($field) . 'Validator';
                $params['validators'][] = new Expr('new ' . ucfirst($field) . 'Validator');
            }
        }

        $templateArgs['dependencyMids'] = ',' . $this->indent($this->implodeMids($templateArgs['dependencyMids']));
        $templateArgs['dependencies'] = ',' . $this->indent($this->implodeNames($templateArgs['dependencies']));
        $templateArgs['mixins'] = $this->indent($this->implodeNames($templateArgs['mixins']), 12) . $this->indent("\n", 8);
        $templateArgs['params'] = $this->implodeParams($params);
        $templateArgs['comment'] = $this->indent("// Will return a validator to validate a complete model for $metadata->name");

        $resource->content = $this->populateTemplate(
            file_get_contents(__DIR__ . '/Template/Module.js.template'),
            $templateArgs
        );

        $this->persistToFile($this->getFilePath($metadata->name), $resource->content);

    }
}
