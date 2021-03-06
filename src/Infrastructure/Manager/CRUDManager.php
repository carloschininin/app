<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Manager;

use CarlosChininin\App\Infrastructure\Repository\BaseRepository;
use CarlosChininin\App\Infrastructure\Security\Permission;
use CarlosChininin\Data\Export\ExportExcel;
use CarlosChininin\Util\Http\ParamFetcher;
use CarlosChininin\Util\Pagination\DoctrinePaginator;
use CarlosChininin\Util\Pagination\PaginatedData;
use CarlosChininin\Util\Pagination\PaginationDto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class CRUDManager extends BaseManager
{
    public function __construct(
        private BaseRepository $repository,
        private Security $security,
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

    public function export(array $items, array $headers, string $fileName = 'export', array $options = []): Response
    {
        $export = new ExportExcel($items, $headers, $options);
        $export->execute()->headerStyle()->columnAutoSize();

        return $export->download($fileName);
    }

    protected function addOwner(object $entity): void
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
