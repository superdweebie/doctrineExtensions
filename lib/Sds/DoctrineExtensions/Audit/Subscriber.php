<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Audit;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\Common\Audit\AuditedInterface;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Annotation\Annotations as Sds;
use Sds\DoctrineExtensions\Annotation\AnnotationEventArgs;
use Sds\DoctrineExtensions\Audit\EventArgs;
use Sds\DoctrineExtensions\Audit\Events as AuditEvents;

/**
 * Implements Sds\Common\Audit\AuditedObjectInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Subscriber implements
    EventSubscriber,
    AnnotationReaderAwareInterface
{
    use AnnotationReaderAwareTrait;

    /**
     * The FQCN used for audits
     *
     * @var string
     */
    protected $auditClass;

    /**
     *
     * @var string
     */
    protected $identityName;

    /**
     *
     * @return string
     */
    public function getAuditClass() {
        return $this->auditClass;
    }

    /**
     * The FQCN used for audits
     *
     * @param string $auditClass
     */
    public function setAuditClass($auditClass) {
        $this->auditClass = (string) $auditClass;
    }

    /**
     *
     * @return string
     */
    public function getIdentityName() {
        return $this->identityName;
    }

    /**
     *
     * @param string $identityName
     */
    public function setIdentityName($identityName) {
        $this->identityName = (string) $identityName;
    }

    /**
     *
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            Sds\Audit::event,
            ODMEvents::onFlush
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Audit\Subscriber\Reader $annotationReader
     * @param string $identityName
     * @param string $auditClass
     */
    public function __construct(
        Reader $annotationReader,
        $identityName = null,
        $auditClass = null
    ){
        $this->setAnnotationReader($annotationReader);
        isset($identityName) ? $this->setIdentityName($identityName) : null;
        isset($auditClass) ? $this->setAuditClass($auditClass) : null;
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Annotation\AnnotationEventArgs $eventArgs
     */
    public function annotationAudit(AnnotationEventArgs $eventArgs)
    {
        $annotation = $eventArgs->getAnnotation();
        $eventArgs->getMetadata()->audit[$eventArgs->getReflection()->getName()] = $annotation->value;
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $documentManager = $eventArgs->getDocumentManager();
        $unitOfWork = $documentManager->getUnitOfWork();

        foreach ($unitOfWork->getScheduledDocumentUpdates() AS $document) {
            if(!$document instanceof AuditedInterface){
                continue;
            }
            $changeSet = $unitOfWork->getDocumentChangeSet($document);
            $metadata = $documentManager->getClassMetadata(get_class($document));
            $eventManager = $documentManager->getEventManager();

            foreach ($changeSet as $field => $change){
                if(isset($metadata->audit[$field]) &&
                    $metadata->audit[$field]
                ){
                    $old = $change[0];
                    $new = $change[1];
                    if($old != $new){
                        $audit = $this->createAudit($old, $new);

                        // Audit created
                        if ($eventManager->hasListeners(AuditEvents::auditCreated)) {
                            $eventManager->dispatchEvent(
                                AuditEvents::auditCreated,
                                new EventArgs($audit, $document, $documentManager)
                            );
                        }

                        $document->addAudit($audit);
                        $unitOfWork->recomputeSingleDocumentChangeSet($metadata, $document);
                    }
                }
            }
        }
    }

    /**
     *
     * @param mixed $old
     * @param mixed $new
     * @return \Sds\DoctrineExtensions\Audit\Subscriber\auditClass
     */
    protected function createAudit($old, $new){
        $auditClass = $this->auditClass;
        return new $auditClass(
            $old,
            $new,
            time(),
            $this->getIdentityName()
        );
    }
}