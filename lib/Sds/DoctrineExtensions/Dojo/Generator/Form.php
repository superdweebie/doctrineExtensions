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
class Form extends AbstractDojoGenerator
{

    const event = 'generatorDojoForm';

    public static function getStaticSubscribedEvents(){
        return [
            self::event,
        ];
    }

    public function getFilePath($className, $fieldName = null){
        return parent::getFilePath($className) . '/Form.js';
    }

    static public function getResourceName($className, $fieldName = null){
        return parent::getResourceName($className) . '/Form.js';
    }

    static public function getMid($className, $fieldName = null){
        return parent::getMid($className) . '/Form';
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Generator\GenerateEventArgs $eventArgs
     */
    public function generatorDojoForm(GenerateEventArgs $eventArgs)
    {

        $metadata = $eventArgs->getDocumentManager()->getClassMetadata($eventArgs->getClassName());
        $options = $eventArgs->getOptions();
        $resource = $eventArgs->getResource();
        $defaultMixins = $this->getDefaultMixins();

        $templateArgs = [];

        $hasMultiFieldValidator = array_key_exists(MultiFieldValidator::getResourceName($metadata->name), $metadata->generator);

        $params = [];

        if ($hasMultiFieldValidator){
            $defaultMids = $defaultMixins['form']['withValidator'];
        } else {
            $defaultMids = $defaultMixins['form']['simple'];
        }
        if (isset($options['mixins'])){
            $templateArgs['dependencyMids'] = $options['mixins'];
        } else {
            $templateArgs['dependencyMids'] = $defaultMids;
        }
        $templateArgs['mixins'] = $this->namesFromMids($templateArgs['dependencyMids']);
        $templateArgs['dependencies'] = $this->namesFromMids($templateArgs['dependencyMids']);
        if ($hasMultiFieldValidator){
            $templateArgs['dependencyMids'][] = MultiFieldValidator::getMid($metadata->name);
            $templateArgs['dependencies'][] = 'MultiFieldValidator';
            $params['validator'] = new Expr('new MultiFieldValidator');
        }
        $params['inputs'] = [];
        foreach($this->getSerializer()->fieldListForUnserialize($metadata) as $field){
            $templateArgs['dependencyMids'][] = Input::getMid($metadata->name, $field);
            $templateArgs['dependencies'][] = ucfirst($field) . 'Input';
            $params['inputs'][] = new Expr('new ' . ucfirst($field) . 'Input');
        }

        $templateArgs['dependencyMids'] = ',' . $this->indent($this->implodeMids($templateArgs['dependencyMids']));
        $templateArgs['dependencies'] = ',' . $this->indent($this->implodeNames($templateArgs['dependencies']));
        $templateArgs['mixins'] = $this->indent($this->implodeNames($templateArgs['mixins']), 12) . $this->indent("\n", 8);
        $templateArgs['params'] = $this->implodeParams($params);
        $templateArgs['comment'] = $this->indent("// Will return a form for $metadata->name");

        $resource->content = $this->populateTemplate(
            file_get_contents(__DIR__ . '/Template/Module.js.template'),
            $templateArgs
        );

        $this->persistToFile($this->getFilePath($metadata->name), $resource->content);
    }
}
