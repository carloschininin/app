<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Security\DoctrineType;

use CarlosChininin\App\Infrastructure\Doctrine\Type\AbstractEnumType;
use CarlosChininin\App\Infrastructure\Security\Permission;

final class PermissionType extends AbstractEnumType
{
    public const string NAME = 'permission';

    public function getName(): string // the name of the type.
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string // the enums class to convert
    {
        return Permission::class;
    }
}
