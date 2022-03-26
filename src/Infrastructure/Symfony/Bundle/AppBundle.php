<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Symfony\Bundle;

use CarlosChininin\App\Infrastructure\Symfony\Bundle\DependencyInjection\CompilerPass\RegisterEnumTypePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterEnumTypePass());
    }
}
