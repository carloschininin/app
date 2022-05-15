<?php

declare(strict_types=1);

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

        dd($entity->file());
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
            $entity->setName($file->getClientOriginalName());
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
