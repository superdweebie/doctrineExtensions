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
class Validator extends AbstractDojoGenerator
{

    const event = 'generatorDojoValidator';

    public function getSubscribedEvents(){
        return [
            self::event,
        ];
    }

    public function getFilePath($className, $fieldName = null){
        return parent::getFilePath($className) . '/' . ucfirst($fieldName) . '/Validator.js';
    }

    static public function getResourceName($className, $fieldName = null){
        return parent::getResourceName($className) . '/' . ucfirst($fieldName) . '/Validator.js';
    }

    static public function getMid($className, $fieldName = null){
        return parent::getMid($className) . '/' . ucfirst($fieldName) . '/Validator';
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Generator\GenerateEventArgs $eventArgs
     */
    public function generatorDojoValidator(GenerateEventArgs $eventArgs)
    {

        $metadata = $eventArgs->getDocumentManager()->getClassMetadata($eventArgs->getClassName());
        $options = $eventArgs->getOptions();
        $resource = $eventArgs->getResource();
        $field = $options['field'];
        $defaultMixins = $this->getDefaultMixins();

        if ( ! isset($metadata->validator['fields'][$field])){
            return;
        }

        if (count($metadata->validator['fields'][$field]) > 1){
            $templateArgs = [
                'dependencyMids' => $defaultMixins['validator']['group'],
                'dependencies' => $this->namesFromMids($defaultMixins['validator']['group']),
                'mixins' => $this->namesFromMids($defaultMixins['validator']['group']),
                'params' => ['field' => "$field"]
            ];

            foreach($metadata->validator['fields'][$field] as $validator){
                $validatorMid = $this->midFromClass($validator['class']);
                $validatorName = $this->nameFromMid($validatorMid);

                $templateArgs['dependencyMids'][] = $validatorMid;
                $templateArgs['dependencies'][] = $validatorName;

                if(isset($validator['options']) && count($validator['options']) > 0 ){
                    $params = json_encode($validator['options']);
                    $templateArgs['params']['validators'][] = new Expr("new $validatorName($params)");
                } else {
                    $templateArgs['params']['validators'][] = new Expr("new $validatorName");
                }
            }

        } else {
            $validator = $metadata->validator['fields'][$field][0];

            $mid = $this->midFromClass($validator['class']);
            $templateArgs = [
                'dependencyMids' => [$mid],
                'dependencies' => [$this->nameFromMid($mid)],
                'mixins' => [$this->nameFromMid($mid)],
            ];

            if (isset($validator['options'])){
                $templateArgs['params'] = array_merge(['field' => "$field"], $validator['options']);
            } else {
                $templateArgs['params'] = ['field' => "$field"];
            }
        }

        $templateArgs['dependencyMids'] = ',' . $this->indent($this->implodeMids($templateArgs['dependencyMids']));
        $templateArgs['dependencies'] = ',' . $this->indent($this->implodeNames($templateArgs['dependencies']));
        $templateArgs['mixins'] = $this->indent($this->implodeNames($templateArgs['mixins']), 12) . $this->indent("\n", 8);
        $templateArgs['params'] = $this->implodeParams($templateArgs['params']);
        $templateArgs['comment'] = $this->indent("// Will return a validator that can be used to check\n// the $field field");

        $resource->content = $this->populateTemplate(
            file_get_contents(__DIR__ . '/Template/Module.js.template'),
            $templateArgs
        );

        $this->persistToFile($this->getFilePath($metadata->name, $field), $resource->content);

    }
}
