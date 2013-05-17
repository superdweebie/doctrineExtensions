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
class MultiFieldValidator extends AbstractDojoGenerator
{

    const event = 'generatorDojoMultiFieldValidator';

    public function getSubscribedEvents(){
        return [
            self::event,
        ];
    }

    public function getFilePath($className, $fieldName = null){
        return parent::getFilePath($className) . '/MultiFieldValidator.js';
    }

    static public function getResourceName($className, $fieldName = null){
        return parent::getResourceName($className) . '/MultiFieldValidator.js';
    }

    static public function getMid($className, $fieldName = null){
        return parent::getMid($className) . '/MultiFieldValidator';
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Generator\GenerateEventArgs $eventArgs
     */
    public function generatorDojoMultiFieldValidator(GenerateEventArgs $eventArgs)
    {

        $metadata = $eventArgs->getDocumentManager()->getClassMetadata($eventArgs->getClassName());
        $options = $eventArgs->getOptions();
        $resource = $eventArgs->getResource();
        $defaultMixins = $this->getDefaultMixins();

        if ( ! isset($metadata->validator['document'])){
            return;
        }


        if (count($metadata->validator['document']) > 1){
            $templateArgs = [
                'dependencyMids' => $defaultMixins['validator']['group'],
                'dependencies' => $this->namesFromMids($defaultMixins['validator']['group']),
                'mixins' => $this->namesFromMids($defaultMixins['validator']['group'])
            ];

            foreach($metadata->validator['document'] as $validator){
                $validatorMid = $this->midFromClass($validator['class']);
                $validatorName = $this->nameFromMid($validatorMid);

                $templateArgs['dependencyMids'][] = $validatorMid;
                $templateArgs['dependencies'][] = $validatorName;

                if(isset($validator['options']) && count($validator['options']) > 0 ){
                    $params = json_encode($validator['options'], JSON_PRETTY_PRINT);
                    $templateArgs['params']['validators'][] = new Expr("new $validatorName($params)");
                } else {
                    $templateArgs['params']['validators'][] = new Expr("new $validatorName");
                }
            }

        } else {
            $validator = $metadata->validator['document'][0];

            $mid = $this->midFromClass($validator['class']);
            $templateArgs = [
                'dependencyMids' => [$mid],
                'dependencies' => [$this->nameFromMid($mid)],
                'mixins' => [$this->nameFromMid($mid)],
            ];

            if (isset($validator['options'])){
                $templateArgs['params'] = array_merge(['field' => "'$field'"], $validator['options']);
            } else {
                $templateArgs['params'] = [];
            }
        }

        $templateArgs['dependencyMids'] = ',' . $this->indent($this->implodeMids($templateArgs['dependencyMids']));
        $templateArgs['dependencies'] = ',' . $this->indent($this->implodeNames($templateArgs['dependencies']));
        $templateArgs['mixins'] = $this->indent($this->implodeNames($templateArgs['mixins']), 12) . $this->indent("\n", 8);
        $templateArgs['params'] = $this->implodeParams($templateArgs['params']);
        $templateArgs['comment'] = $this->indent("// Will return a multi field validator");

        $resource->content = $this->populateTemplate(
            file_get_contents(__DIR__ . '/Template/Module.js.template'),
            $templateArgs
        );

        $this->persistToFile($this->getFilePath($metadata->name), $resource->content);

    }
}
