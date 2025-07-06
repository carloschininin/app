<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('fecha', [DateRuntime::class, 'dateFilter']),
            new TwigFilter('fecha_media', [DateRuntime::class, 'dateMediumFilter']),
            new TwigFilter('fecha_larga', [DateRuntime::class, 'dateLargeFilter']),
            new TwigFilter('fecha_formato', [DateRuntime::class, 'dateFormatFilter']),
            new TwigFilter('fecha_hora', [DateRuntime::class, 'dateTimeFilter']),
            new TwigFilter('fecha_hora_larga', [DateRuntime::class, 'dateTimeLargeFilter']),
        ];
    }
}
