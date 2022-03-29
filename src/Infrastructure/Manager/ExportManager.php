<?php

declare(strict_types=1);

namespace CarlosChininin\App\Infrastructure\Manager;

use CarlosChininin\Data\Export\ExportExcel;
use Symfony\Component\HttpFoundation\Response;

class ExportManager extends BaseManager
{
    public function execute(array $items, array $headers, string $fileName = 'export', array $options = []): Response
    {
        $export = new ExportExcel($items, $headers, $options);
        $export->execute()->headerStyle()->columnAutoSize();

        return $export->download($fileName);
    }
}