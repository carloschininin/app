<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Domain\Model\AuthRole;

use CarlosChininin\App\Infrastructure\Security\MenuPermission;

abstract class AuthRole
{
    /** @return MenuPermission[] */
    abstract public function permissions(): array;

    abstract public function role(): string;
}
