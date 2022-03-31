<?php

declare(strict_types=1);

namespace CarlosChininin\App\Infrastructure\Twig\Extension;

use CarlosChininin\Util\Pagination\PaginatedData;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PaginationExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('index', [$this, 'indexFilter']),
            new TwigFilter('indexReverse', [$this, 'indexReverseFilter']),
            new TwigFilter('indexCountReverse', [$this, 'indexCountReverseFilter']),
        ];
    }

    public function indexFilter(int $index, PaginatedData $paginator): int
    {
        return $index + ($paginator->currentPage() - 1) * $paginator->pageSize();
    }

    public function indexReverseFilter(int $index, PaginatedData $paginator): int
    {
        return ($paginator->numResults() + 1) - ($index + ($paginator->currentPage() - 1) * $paginator->pageSize());
    }

    public function indexCountReverseFilter(int $index, int $total): int
    {
        return ($total + 1) - $index;
    }
}