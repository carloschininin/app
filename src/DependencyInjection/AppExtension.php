<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class AppExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        var_dump('We\'re alive!');
        exit;
    }
}
