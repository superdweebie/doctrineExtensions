<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\DojoModel;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\ClassNamePropertyTrait;

/**
 * Adds dojoModel values to classmetadata
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Subscriber implements EventSubscriber, AnnotationReaderAwareInterface
{
    use AnnotationReaderAwareTrait;
    use ClassNamePropertyTrait;

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            Sds\Dojo::event
        );
    }

    /**
     *
     * @param \Doctrine\Common\Annotations\Reader $annotationReader
     * @param string $className
     */
    public function __construct(Reader $annotationReader, $classNameProperty){
        $this->setAnnotationReader($annotationReader);
        $this->setClassNameProperty($classNameProperty);
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationDojo(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();

        $dojoMetadata = [];

        if (is_array($annotation->value)){
            foreach ($annotation->value as $subAnnotation){
                $dojoMetadata = $this->processAnnotation($subAnnotation, $dojoMetadata);
            }
        } else {
            $dojoMetadata = $this->processAnnotation($annotation->value, $dojoMetadata);
        }

        switch ($eventArgs->getEventType()){
            case 'document':
                $dojoMetadata['classNameProperty'] = $this->getClassNameProperty();
                $eventArgs->getMetadata()->dojo = $dojoMetadata;
                break;
            case 'property':
                $eventArgs->getMetadata()->dojo['fields'][$eventArgs->getReflection()->getName()] = $dojoMetadata;
                break;
        }
    }

    protected function processAnnotation($annotation, $dojoMetadata){

        switch (true){
            case ($annotation instanceof Sds\ClassName):
                $dojoMetadata['className'] = $annotation->value;
                break;
            case ($annotation instanceOf Sds\Discriminator):
                $dojoMetadata['discriminator'] = $annotation->value;
                break;
            case ($annotation instanceOf Sds\InheritFrom):
                $dojoMetadata['inheritFrom'] = $annotation->value;
                break;
            case ($annotation instanceOf Sds\Metadata):
                $dojoMetadata['metadata'] = $annotation->value;
                break;
            case ($annotation instanceOf Sds\ValidatorGroup):
                $dojoMetadata['validatorGroup'] = [];
                foreach($annotation->value as $validator){
                    switch (true){
                        case ($validator instanceof Sds\Validator):
                            $dojoMetadata['validatorGroup'][] = [
                                'class' => $validator->class,
                                'options' => $validator->options
                            ];
                            break;
                        case ($validator instanceof Sds\Required):
                            $dojoMetadata['required'] = $validator->value;
                            break;
                    }
                }
                break;
            case ($annotation instanceOf Sds\Ignore):
                $dojoMetadata['ignore'] = $annotation->value;
                break;
        }

        return $dojoMetadata;
    }
}