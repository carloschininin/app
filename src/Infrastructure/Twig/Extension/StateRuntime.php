<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Twig\Extension;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

final readonly class StateRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    public function yesnoFilter(?bool $value): string
    {
        $text = (null === $value || false === $value) ? 'no' : 'yes';

        return $this->translator->trans($text);
    }

    public function yesnoCustomFilter(?bool $value): string
    {
        $text = self::yesnoFilter($value);
        $class = (null === $value || false === $value) ? 'secondary' : 'success';

        return "<span class='badge badge-$class bg-$class'>".$text.'</span>';
    }
}
