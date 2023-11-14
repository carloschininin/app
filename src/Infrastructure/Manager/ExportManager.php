<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Manager;

use CarlosChininin\Spreadsheet\Writer\OpenSpout\SpreadsheetWriter;
use CarlosChininin\Spreadsheet\Writer\WriterOptions;
use Symfony\Component\HttpFoundation\Response;

class ExportManager extends BaseManager
{
    public function execute(
        array $items,
        array $headers,
        string $fileName = 'export',
        WriterOptions $options = new WriterOptions()
    ): Response {
        $export = new SpreadsheetWriter($items, $headers, $options);

        return $export->execute(false)->download($fileName);
    }
}
