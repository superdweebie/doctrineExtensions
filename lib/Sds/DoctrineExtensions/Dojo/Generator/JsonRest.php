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
class JsonRest extends AbstractDojoGenerator
{

    const event = 'generatorDojoJsonRest';

    public function getSubscribedEvents(){
        return [
            self::event,
        ];
    }

    public function getFilePath($className, $fieldName = null){
        return parent::getFilePath($className) . '/JsonRest.js';
    }

    static public function getResourceName($className, $fieldName = null){
        return parent::getResourceName($className) . '/JsonRest.js';
    }

    static public function getMid($className, $fieldName = null){
        return parent::getMid($className) . '/JsonRest';
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Generator\GenerateEventArgs $eventArgs
     */
    public function generatorDojoJsonRest(GenerateEventArgs $eventArgs)
    {

        $options = $eventArgs->getOptions();
        $resource = $eventArgs->getResource();
        $metadata = $eventArgs->getDocumentManager()->getClassMetadata($eventArgs->getClassName());
        $defaultMixins = $this->getDefaultMixins();

        $templateArgs = [];

        $params = [];

        if (isset($options['mixins'])){
            $templateArgs['dependencyMids'] = $options['mixins'];
        } else {
            $templateArgs['dependencyMids'] = $defaultMixins['store']['jsonRest'];
        }
        $templateArgs['mixins'] = $this->namesFromMids($templateArgs['dependencyMids']);
        $templateArgs['dependencies'] = $this->namesFromMids($templateArgs['dependencyMids']);

        $modelMid = Model::getMid($metadata->name);
        $pieces = explode('/', $modelMid);
        $model = $pieces[count($pieces) - 1];

        $templateArgs['dependencyMids'][] = $modelMid;
        $templateArgs['dependencies'][] = $model;

        $params['name'] = $metadata->collection;
        $params['idField'] = $metadata->identifier;

        if (isset($metadata->rest)){
            $params['target'] = $metadata->rest['endpoint'];
        } else {
            $params['target'] = $metadata->collection;
        }
        $params['model'] = new Expr($model);

        $templateArgs['dependencyMids'] = ',' . $this->indent($this->implodeMids($templateArgs['dependencyMids']));
        $templateArgs['dependencies'] = ',' . $this->indent($this->implodeNames($templateArgs['dependencies']));
        $templateArgs['mixins'] = $this->indent($this->implodeNames($templateArgs['mixins']), 12) . $this->indent("\n", 8);
        $templateArgs['params'] = $this->implodeParams($params);
        $templateArgs['comment'] = $this->indent("// Will return create a dojo JsonRest store for $metadata->name");

        $resource->content = $this->populateTemplate(
            file_get_contents(__DIR__ . '/Template/Module.js.template'),
            $templateArgs
        );

        $this->persistToFile($this->getFilePath($metadata->name), $resource->content);

    }
}
