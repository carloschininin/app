<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Controller;

use CarlosChininin\App\Infrastructure\Security\Security;
use CarlosChininin\Util\Error\Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
    public function __construct(private Security $security)
    {
    }

    protected function denyAccess(array $permissions, string $menuRoute, ?object $entity = null, string $message = 'Acceso denegado...'): void
    {
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

    /**
     * @param Error[] $errors
     */
    protected function addErrors(array $errors): void
    {
        foreach ($errors as $error) {
            $this->addFlash('danger', $error->message());
        }
    }
}
