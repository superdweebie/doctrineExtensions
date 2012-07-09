<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace SdsDoctrineExtensions\Audit\Subscriber;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Events as ODMEvents;
use SdsCommon\Audit\AuditedInterface;
use SdsCommon\User\ActiveUserAwareTrait;
use SdsCommon\User\ActiveUserAwareInterface;
use SdsCommon\User\UserInterface;
use SdsDoctrineExtensions\AnnotationReaderAwareTrait;
use SdsDoctrineExtensions\AnnotationReaderAwareInterface;
use SdsDoctrineExtensions\Audit\Event\EventArgs;
use SdsDoctrineExtensions\Audit\Event\Events as AuditEvents;
use SdsDoctrineExtensions\Audit\Mapping\MetadataInjector\Audit as MetadataInjector;

/**
 * Implements SdsCommon\Audit\AuditedObjectInterface
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
     * @param \SdsDoctrineExtensions\Audit\Subscriber\Reader $annotationReader
     * @param \SdsCommon\User\UserInterface $activeUser
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
     * @return \SdsDoctrineExtensions\Audit\Subscriber\auditClass
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