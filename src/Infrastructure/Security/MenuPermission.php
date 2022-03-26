<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Security;

use JsonSerializable;

final class MenuPermission implements JsonSerializable
{
    public function __construct(
        private string $menu,
        private array $atributos,
    ) {
    }

    public function menu(): string
    {
        return $this->menu;
    }

    public function atributos(): array
    {
        return $this->atributos;
    }

    public function jsonSerialize(): array
    {
        return [
            'menu' => $this->menu(),
            'attr' => $this->atributos(),
        ];
    }
}
