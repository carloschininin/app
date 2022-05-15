<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\EventSubscriber;

use CarlosChininin\App\Domain\Model\AttachedFile\AttachedFile;
use CarlosChininin\App\Domain\Model\AttachedFile\Event\AttachedFilePersistEvent;
use CarlosChininin\App\Infrastructure\Service\FileUploader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachedFileDoctrineSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly FileUploader $fileUploader)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AttachedFilePersistEvent::NAME => 'onPersist',
        ];
    }

    public function onPersist(AttachedFilePersistEvent $event): void
    {
        $attachedFile = $event->attachedFile();

        $this->uploadFile($attachedFile);
    }

//    public function prePersist(LifecycleEventArgs $args): void
//    {
//        $entity = $args->getEntity();
//
//        $this->uploadFile($entity);
//    }
//
//    public function preUpdate(LifecycleEventArgs $args): void
//    {
//        $entity = $args->getEntity();
//
//        if (!$entity instanceof AttachedFile) {
//            return;
//        }
//
//        $entity->setPreviousPath($entity->path());
//        $this->uploadFile($entity);
//    }
//
//    public function preRemove(LifecycleEventArgs $args): void
//    {
//        $entity = $args->getEntity();
//
//        $this->removeFile($entity);
//    }

    private function uploadFile($entity): void
    {
        if (!$entity instanceof AttachedFile) {
            return;
        }

        $file = $entity->file();

        if ($file instanceof UploadedFile) {
            $originalFilename = pathinfo($file->getClientOriginalName(), \PATHINFO_FILENAME);
            $entity->setName($originalFilename);

            $secure = $this->fileUploader->upload($file, $entity->path());
            $entity->setSecure($secure);

            $this->fileUploader->remove($entity->previousPath());
        }
    }

    private function removeFile($entity): void
    {
        if (!$entity instanceof AttachedFile) {
            return;
        }

        $nombre = $entity->path();
        $this->fileUploader->remove($nombre);
    }
}
