<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Controller;

use CarlosChininin\App\Infrastructure\Security\Security;
use Symfony\Component\HttpFoundation\Response;

abstract class WebAuthController extends WebController
{
    public const BASE_ROUTE = 'undefined';

    public function __construct(private Security $security)
    {
    }

    protected function denyAccess(array $permissions, ?string $menuRoute = null, ?object $entity = null, string $message = 'Acceso denegado...'): void
    {
        $menuRoute = $menuRoute ?? self::BASE_ROUTE;

        $this->security->denyAccessUnlessGranted($permissions, $menuRoute, $entity, $message);
    }

//    protected function hasAccess(string $attribute, string $subject, object $object = null): bool
//    {
//        return $this->security->hasAccess($attribute, $subject, $object);
//    }

    protected function isSuperAdmin(): bool
    {
        return $this->security->isSuperAdmin();
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $parameters = array_merge($parameters, ['access' => $this->security]);

        return parent::render($view, $parameters, $response);
    }
}
