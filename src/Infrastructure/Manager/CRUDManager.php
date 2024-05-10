<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Manager;

use CarlosChininin\App\Infrastructure\Repository\BaseRepository;
use CarlosChininin\App\Infrastructure\Security\Permission;
use CarlosChininin\Spreadsheet\Writer\OpenSpout\SpreadsheetWriter;
use CarlosChininin\Spreadsheet\Writer\WriterOptions;
use CarlosChininin\Util\Http\ParamFetcher;
use CarlosChininin\Util\Pagination\DoctrinePaginator;
use CarlosChininin\Util\Pagination\PaginatedData;
use CarlosChininin\Util\Pagination\PaginationDto;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * @template T of object
 */
class CRUDManager extends BaseManager
{
    public const PAGE_LIMIT = 5;

    public function __construct(
        private readonly BaseRepository $repository,
        private readonly Security $security,
    ) {
    }

    /**
     * @param T $entity
     */
    public function save(mixed $entity): bool
    {
        $this->addOwner($entity);

        $this->repository->save($entity);

        return true;
    }

    /**
     * @param T $entity
     */
    public function remove(mixed $entity): bool
    {
        $this->repository->remove($entity);

        return true;
    }

    /**
     * @return iterable<T>|array
     */
    public function dataList(ParamFetcher $params, bool $inArray = false): array
    {
        return $this->repository->filter($params, $inArray, [Permission::LIST_ALL]);
    }

    /**
     * @return iterable<T>|array
     */
    public function dataExport(ParamFetcher $params, bool $inArray = false): array
    {
        return $this->repository->filter($params, $inArray, [Permission::EXPORT_ALL]);
    }

    /**
     * @return PaginatedData<T>
     */
    public function paginate(int $page, ParamFetcher $params): PaginatedData
    {
        $limit = $params->getNullableInt('limit') ?? $params->getNullableInt('n');
        $pagination = PaginationDto::create($page, $limit ?? self::PAGE_LIMIT);
        $dataQuery = $this->repository->filterQuery($params, [Permission::LIST_ALL]);

        return (new DoctrinePaginator())->paginate($dataQuery, $pagination);
    }

    public function export(
        array $items,
        array $headers,
        string $fileName = 'export',
        WriterOptions $options = new WriterOptions()
    ): Response {
        $export = new SpreadsheetWriter($items, $headers, $options);

        return $export->execute(false)->download($fileName);
    }

    /**
     * @param T $entity
     */
    protected function addOwner(mixed $entity): void
    {
        if (method_exists($entity, 'propietario')
            && method_exists($entity, 'setPropietario')
            && null === $entity->propietario()
        ) {
            $entity->setPropietario($this->security->getUser());

            return;
        }

        if (method_exists($entity, 'owner')
            && method_exists($entity, 'setOwner')
            && null === $entity->owner()
        ) {
            $entity->setOwner($this->security->getUser());
        }
    }
}
