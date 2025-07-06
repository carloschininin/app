<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Manager;

use CarlosChininin\Util\Http\ParamFetcher;
use CarlosChininin\Util\Pagination\PaginatedData;
use CarlosChininin\Util\Pagination\PaginationDto;
use CarlosChininin\Util\Pagination\PaginatorInterface;

class ListPaginatedManager extends BaseManager
{
    public function __construct(
        private readonly PaginatorInterface $paginator,
    ) {
    }

    public function execute(mixed $data, int $page, ParamFetcher $params): PaginatedData
    {
        $pagination = PaginationDto::create($page, $params->getNullableInt('limit'));

        return $this->paginator->paginate($data, $pagination);
    }
}
