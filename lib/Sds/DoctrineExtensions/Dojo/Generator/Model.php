<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Dojo\Generator;

use Sds\DoctrineExtensions\Generator\GenerateEventArgs;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Model extends AbstractDojoGenerator
{

    const event = 'generatorDojoModel';

    public static function getStaticSubscribedEvents(){
        return [
            self::event,
        ];
    }

    public function getFilePath($className, $fieldName = null){
        return parent::getFilePath($className) . '.js';
    }

    static public function getResourceName($className, $fieldName = null){
        return parent::getResourceName($className) . '.js';
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Generator\GenerateEventArgs $eventArgs
     */
    public function generatorDojoModel(GenerateEventArgs $eventArgs)
    {

        $options = $eventArgs->getOptions();
        $resource = $eventArgs->getResource();
        $metadata = $eventArgs->getDocumentManager()->getClassMetadata($eventArgs->getClassName());
        $defaultMixins = $this->getDefaultMixins();

        $templateArgs = [];

        $mid = self::getMid($metadata->name);

        $params = [];

        if (isset($options['mixins'])){
            $templateArgs['dependencyMids'] = $options['mixins'];
        } else {
            $templateArgs['dependencyMids'] = $defaultMixins['model'];
        }

        $templateArgs['mixins'] = $this->namesFromMids($templateArgs['dependencyMids']);
        $templateArgs['dependencies'] = $this->namesFromMids($templateArgs['dependencyMids']);

        $params['_fields'] = [];
        foreach($this->getSerializer()->fieldListForUnserialize($metadata) as $field){
            $params['_fields'][] = $field;
        }

        $params['_fields'][] = '_className';
        $params['_className'] = str_replace('\\', '\\\\', $metadata->name);

        $templateArgs['dependencyMids'] = ',' . $this->indent($this->implodeMids($templateArgs['dependencyMids']));
        $templateArgs['dependencies'] = ',' . $this->indent($this->implodeNames($templateArgs['dependencies']));
        $templateArgs['mixins'] = $this->indent($this->implodeNames($templateArgs['mixins']), 12) . $this->indent("\n", 8);
        $templateArgs['params'] = $this->implodeParams($params);
        $templateArgs['comment'] = $this->indent("// Will return a model for $metadata->name");

        $resource->content = $this->populateTemplate(
            file_get_contents(__DIR__ . '/Template/Module.js.template'),
            $templateArgs
        );

        $this->persistToFile($this->getFilePath($metadata->name), $resource->content);
    }
}
