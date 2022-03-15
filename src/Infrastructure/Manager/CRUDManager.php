<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Manager;

use CarlosChininin\App\Infrastructure\Entity\BaseEntity;
use CarlosChininin\App\Infrastructure\Repository\BaseRepository;
use Symfony\Component\Security\Core\Security;

class CRUDManager extends BaseManager
{
    public function __construct(
        private BaseRepository $repository,
        private Security $security,
    ) {
    }

    public function save(BaseEntity $entity): bool
    {
        $this->addOwner($entity);

        $this->repository->save($entity);

        return true;
    }

    public function remove(BaseEntity $entity): bool
    {
        $this->repository->remove($entity);

        return true;
    }

    public function addOwner(BaseEntity $entity): void
    {
        if (!method_exists($entity, 'propietario') || !method_exists($entity, 'setPropietario')) {
            return;
        }

        if (null !== $entity->propietario()) {
            return;
        }

        $entity->setPropietario($this->security->getUser());
    }
}
