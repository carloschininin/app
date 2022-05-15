<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\EventSubscriber;

use CarlosChininin\App\Domain\Model\AttachedFile\AttachedFile;
use CarlosChininin\App\Infrastructure\Service\FileUploader;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachedFileDoctrineSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly FileUploader $fileUploader)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof AttachedFile) {
            return;
        }

        $entity->setPreviousPath($entity->path());
        $this->uploadFile($entity);
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->removeFile($entity);
    }

    private function uploadFile($entity): void
    {
        if (!$entity instanceof AttachedFile) {
            return;
        }

        $file = $entity->file();

        if ($file instanceof UploadedFile) {
            $previusPath = $entity->path();

            $secure = $this->fileUploader->upload($file);
            $entity->setSecure($secure);

            $this->fileUploader->remove($previusPath);
        }
    }

    private function removeFile($entity): void
    {
        if (!$entity instanceof AttachedFile) {
            return;
        }

        $this->fileUploader->remove($entity->path());
    }
}
