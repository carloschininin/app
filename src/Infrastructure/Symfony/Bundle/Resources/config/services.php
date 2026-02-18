<?php

use CarlosChininin\App\Infrastructure\EventSubscriber\RedirectToPreferredLocaleSubscriber;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use CarlosChininin\App\Infrastructure\Doctrine\Type\AbstractEnumType;
use CarlosChininin\App\Domain\Model\AuthMenu\MenuServiceInterface;
use CarlosChininin\Util\Pagination\DoctrinePaginator;
use CarlosChininin\Util\Pagination\PaginatorInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $configurator): void {

    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private()
        ->bind('$locales', '%app_locales%')
        ->bind('$defaultLocale', '%locale%');

    /*
     * Auto discovery
     */
    $services->load('CarlosChininin\\App\\', '../../../../../../src/*')
        ->exclude([
            '../../../../../../src/Infrastructure/Symfony',
            '../../../../../../src/Infrastructure/Entity',
        ]);

    /*
     * instanceof rules
     */
    $services->instanceof(AbstractEnumType::class)
        ->tag('app.doctrine_enum_type');

    $services->instanceof(MenuServiceInterface::class)
        ->public()
        ->tag('app.menu_service');

    /*
     * Paginator explícito (si no está dentro del namespace cargado)
     */
    $services->set(DoctrinePaginator::class)
        ->tag('app.paginator');

    /*
     * Aliases limpios (forma recomendada)
     */
    $services->alias(PaginatorInterface::class, DoctrinePaginator::class);
};
