<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TextExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('filter_text', [$this, 'filterText']),
        ];
    }

    public function filterText(?string $text): string
    {
        if (null === $text) {
            return '';
        }

        return preg_replace('([^A-Za-z0-9])', ' ', $text);
    }
}
