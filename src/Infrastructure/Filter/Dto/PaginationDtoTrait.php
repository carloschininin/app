<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Filter\Dto;

use CarlosChininin\App\Infrastructure\Manager\CRUDManager;

trait PaginationDtoTrait
{
    public ?int $page = 1;
    public ?int $limit = CRUDManager::PAGE_LIMIT;

    public function paginationToArray(): array
    {
        return [
            'page' => $this->page,
            'limit' => $this->limit,
        ];
    }
}
