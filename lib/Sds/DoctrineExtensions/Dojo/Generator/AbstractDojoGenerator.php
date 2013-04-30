<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Dojo\Generator;

use Sds\DoctrineExtensions\AbstractLazySubscriber;
use Sds\DoctrineExtensions\Generator\GeneratorInterface;
use Zend\Json\Expr;
use Zend\Json\Json;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
abstract class AbstractDojoGenerator extends AbstractLazySubscriber implements GeneratorInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    protected $extension;

    protected $serializer;

    protected $filePaths;

    protected $defaultMixins;

    protected $persistToFile;

    public function getFilePaths(){
        if (!isset($this->filePaths)){
            $this->filePaths = $this->getExtension()->getFilePaths();
        }
        return $this->filePaths;
    }

    public function getDefaultMixins(){
        if (!isset($this->defaultMixins)){
            $this->defaultMixins = $this->getExtension()->getDefaultMixins();
        }
        return $this->defaultMixins;
    }

    public function getPersistToFile(){
        if (!isset($this->persistToFile)){
            $this->persistToFile = $this->getExtension()->getPersistToFile();
        }
        return $this->persistToFile;
    }

    public function getExtension(){
        if (!isset($this->extension)){
            $this->extension = $this->serviceLocator->get('Sds\DoctrineExtensions\Dojo\Extension');
        }
        return $this->extension;
    }

    protected function getSerializer(){
        if (!isset($this->serializer)){
            $this->serializer = $this->serviceLocator->get('serializer');
        }
        return $this->serializer;
    }

    public function getFilePath($className, $fieldName = null){
        foreach ($this->getFilePaths() as $filePath){
            if ($filePath['filter'] == '' || strpos($className, $filePath['filter']) !== false) {
                return $filePath['path'] . '/' . self::getMid($className);
                break;
            }
        }
    }

    static public function getResourceName($className, $fieldName = null){
        return self::getMid($className);
    }

    static public function getMid($className, $fieldName = null){
        return str_replace('\\', '/', $className);
    }

    protected function populateTemplate($template, array $args) {

        $populated = $template;
        foreach ($args as $key => $value) {
            $populated = str_replace('<'.$key.'>', $value, $populated);
        }
        return $populated;
    }

    protected function indent($string, $indent = 4){
        $indent = str_repeat(' ', $indent);
        return $indent . str_replace("\n", "\n" . $indent, $string);
    }

    protected function implodeMids(array $mids){
        return "\n'" . implode("',\n'", $mids) . "'";
    }

    protected function implodeParams(array $params){
        $tmp = [];
        foreach ($params as $key => $value){
            switch (true){
                case is_array($value):
                    $param = "$key: " . Json::prettyPrint(Json::encode(
                        $value,
                        false,
                        ['enableJsonExprFinder' => true]
                    ));
                    break;
                case ($value instanceof Expr):
                    $param = "$key: " . $value;
                    break;
                default:
                    $param = "$key: '$value'";
            }
            $tmp[] = $param;
        }
        return $this->indent(implode(",\n\n", $tmp), 12);
    }

    protected function implodeNames(array $names){
        return "\n" . implode(",\n", $names);
    }

    protected function namesFromMids(array $mids){
        $result = [];
        foreach($mids as $mid){
            $result[] = $this->nameFromMid($mid);
        }
        return $result;
    }

    protected function nameFromMid($mid){
        $pieces = explode('/', $mid);
        return $pieces[count($pieces) - 1];
    }

    protected function midFromClass($class){
        return str_replace('\\', '/', $class);
    }

    protected function persistToFile($filePath, $content){
        if ($this->getPersistToFile()){
            if ($filePath){

                $dir = dirname($filePath);

                if ( ! is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }

                file_put_contents($filePath, $content);
            }
        }
    }
}
