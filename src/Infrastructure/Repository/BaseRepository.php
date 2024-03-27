<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Repository;

use CarlosChininin\Util\Http\ParamFetcher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class BaseRepository extends ServiceEntityRepository
{
    public function filter(ParamFetcher|array $params, bool $inArray = false, array $permissions = []): array
    {
        $queryBuilder = $this->filterQuery($params, $permissions)->getQuery();

        return true === $inArray ? $queryBuilder->getArrayResult() : $queryBuilder->getResult();
    }

    public function all(bool $inArray = false): array
    {
        $queryBuilder = $this->allQuery()->getQuery();

        return true === $inArray ? $queryBuilder->getArrayResult() : $queryBuilder->getResult();
    }

    public function save(mixed $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(mixed $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    abstract public function filterQuery(ParamFetcher|array $params, array $permissions = []): QueryBuilder;

    abstract public function allQuery(): QueryBuilder;
}
