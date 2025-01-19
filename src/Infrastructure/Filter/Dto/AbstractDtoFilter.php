<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Filter\Dto;

abstract class AbstractDtoFilter
{
    use PaginationDtoTrait;
    use TextSearchDtoTrait;
}
