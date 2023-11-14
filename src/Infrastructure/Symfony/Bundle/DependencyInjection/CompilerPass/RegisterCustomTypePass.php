<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Symfony\Bundle\DependencyInjection\CompilerPass;

use CarlosChininin\App\Infrastructure\Security\DoctrineType\MenuPermissionType;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterCustomTypePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $typesDefinition = [];
        if ($container->hasParameter('doctrine.dbal.connection_factory.types')) {
            $typesDefinition = $container->getParameter('doctrine.dbal.connection_factory.types');
        }

        $typesDefinition[MenuPermissionType::NAME] = ['class' => MenuPermissionType::class];

        $container->setParameter('doctrine.dbal.connection_factory.types', $typesDefinition);
    }
}
