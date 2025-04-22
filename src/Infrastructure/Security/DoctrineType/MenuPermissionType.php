<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Security\DoctrineType;

use CarlosChininin\App\Infrastructure\Security\MenuPermission;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

final class MenuPermissionType extends JsonType
{
    public const string NAME = 'menu_permission_json';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?array
    {
        $items = parent::convertToPHPValue($value, $platform);
        if (null === $items) {
            return null;
        }

        $data = [];
        foreach ($items as $item) {
            $data[] = new MenuPermission($item['menu'], $item['attr']);
        }

        return $data;
    }
}
