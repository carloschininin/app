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
//    public function findLatest(array $params): Paginator
//    {
//        $queryBuilder = $this->filterQuery($params);
//
//        return Paginator::create($queryBuilder, $params);
//    }

    public function filter(ParamFetcher|array $params, bool $inArray = false): array
    {
        $queryBuilder = $this->filterQuery($params)->getQuery();

        return true === $inArray ? $queryBuilder->getArrayResult() : $queryBuilder->getResult();
    }

    public function all(bool $inArray = false): array
    {
        $queryBuilder = $this->allQuery()->getQuery();

        return true === $inArray ? $queryBuilder->getArrayResult() : $queryBuilder->getResult();
    }

    public function save(mixed $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(mixed $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    abstract public function filterQuery(ParamFetcher|array $params): QueryBuilder;

    abstract public function allQuery(): QueryBuilder;
}
