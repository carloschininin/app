<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Domain\Model\Menu;

interface MenuService
{
    public function menusToArray(): array;
}
