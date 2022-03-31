<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class StateExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('yesno', [StateRuntime::class, 'yesnoFilter']),
            new TwigFilter('yesno_custom', [StateRuntime::class, 'yesnoCustomFilter']),
        ];
    }
}
