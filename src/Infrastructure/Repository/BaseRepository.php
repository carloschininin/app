<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Repository;

use CarlosChininin\Util\Http\ParamFetcher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @template T of object
 *
 * @template-extends ServiceEntityRepository<T>
 */
abstract class BaseRepository extends ServiceEntityRepository
{
    /**
     * @return iterable<T>|array
     */
    public function filter(ParamFetcher|array $params, bool $inArray = false, array $permissions = []): array
    {
        $queryBuilder = $this->filterQuery($params, $permissions)->getQuery();

        return true === $inArray ? $queryBuilder->getArrayResult() : $queryBuilder->getResult();
    }

    /**
     * @return iterable<T>|array
     */
    public function all(bool $inArray = false): array
    {
        $queryBuilder = $this->allQuery()->getQuery();

        return true === $inArray ? $queryBuilder->getArrayResult() : $queryBuilder->getResult();
    }

    /**
     * @param T $entity
     */
    public function save(mixed $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param T $entity
     */
    public function remove(mixed $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function filterPaginateQuery(ParamFetcher|array $params, array $permissions = []): QueryBuilder
    {
        return $this->filterQuery($params, $permissions);
    }

    public function filterExportQuery(ParamFetcher|array $params, array $permissions = []): QueryBuilder
    {
        return $this->filterQuery($params, $permissions);
    }

    abstract public function filterQuery(ParamFetcher|array $params, array $permissions = []): QueryBuilder;

    abstract public function allQuery(): QueryBuilder;
}
