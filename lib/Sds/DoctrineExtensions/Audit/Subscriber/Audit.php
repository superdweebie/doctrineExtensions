<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\Audit\Subscriber;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use Sds\Common\Audit\AuditedInterface;
use Sds\Common\User\ActiveUserAwareTrait;
use Sds\Common\User\ActiveUserAwareInterface;
use Sds\Common\User\UserInterface;
use Sds\DoctrineExtensions\AnnotationReaderAwareTrait;
use Sds\DoctrineExtensions\AnnotationReaderAwareInterface;
use Sds\DoctrineExtensions\Audit\Event\EventArgs;
use Sds\DoctrineExtensions\Audit\Event\Events as AuditEvents;
use Sds\DoctrineExtensions\Audit\Mapping\MetadataInjector\Audit as MetadataInjector;

/**
 * Implements Sds\Common\Audit\AuditedObjectInterface
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class Audit implements
    EventSubscriber,
    AnnotationReaderAwareInterface,
    ActiveUserAwareInterface
{
    use ActiveUserAwareTrait;
    use AnnotationReaderAwareTrait;

    /**
     * The FQCN used for audits
     *
     * @var string
     */
    protected $auditClass;

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
     * @return array
     */
    public function getSubscribedEvents(){
        return array(
            ODMEvents::loadClassMetadata,
            ODMEvents::onFlush
        );
    }

    /**
     *
     * @param \Sds\DoctrineExtensions\Audit\Subscriber\Reader $annotationReader
     * @param \Sds\Common\User\UserInterface $activeUser
     * @param string $auditClass
     */
    public function __construct(
        Reader $annotationReader,
        UserInterface $activeUser = null,
        $auditClass = null
    ){
        $this->setAnnotationReader($annotationReader);
        isset($activeUser) ? $this->setActiveUser($activeUser) : null;
        isset($auditClass) ? $this->setAuditClass($auditClass) : null;
    }

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        $metadataInjector = new MetadataInjector($this->annotationReader);
        $metadataInjector->loadMetadataForClass($metadata);
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
                if(isset($metadata->fieldMappings[$field][MetadataInjector::audit]) &&
                    $metadata->fieldMappings[$field][MetadataInjector::audit]
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
        $activeUsername = null;
        if($this->activeUser){
            $activeUsername = $this->activeUser->getUsername();
        }
        $auditClass = $this->auditClass;
        return new $auditClass(
            $old,
            $new,
            time(),
            $activeUsername
        );
    }
}