<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Security;

readonly final class MenuPermission implements \JsonSerializable, \Stringable
{
    public function __construct(
        private string $menu,
        private array $attributes,
    ) {
    }

    public function __toString()
    {
        return $this->menu();
    }

    public function menu(): string
    {
        return $this->menu;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function jsonSerialize(): array
    {
        return [
            'menu' => $this->menu(),
            'attr' => $this->attributes(),
        ];
    }
}
