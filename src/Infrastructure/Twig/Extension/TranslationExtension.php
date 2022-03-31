<?php

declare(strict_types=1);

namespace CarlosChininin\App\Infrastructure\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TranslationExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('gtrans', [TranslationRuntime::class, 'googleTranslate']),
        ];
    }
}