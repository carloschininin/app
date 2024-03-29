<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
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

class CRUDManager extends BaseManager
{
    public function __construct(
        private readonly BaseRepository $repository,
        private readonly Security $security,
    ) {
    }

    public function save(object $entity): bool
    {
        $this->addOwner($entity);

        $this->repository->save($entity);

        return true;
    }

    public function remove(object $entity): bool
    {
        $this->repository->remove($entity);

        return true;
    }

    public function dataList(ParamFetcher $params, bool $inArray = false): array
    {
        return $this->repository->filter($params, $inArray, [Permission::LIST_ALL]);
    }

    public function dataExport(ParamFetcher $params, bool $inArray = false): array
    {
        return $this->repository->filter($params, $inArray, [Permission::EXPORT_ALL]);
    }

    public function paginate(int $page, ParamFetcher $params): PaginatedData
    {
        $limit = $params->getNullableInt('limit') ?? $params->getNullableInt('n');
        $pagination = PaginationDto::create($page, $limit);
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

    protected function addOwner(object $entity): void
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
