<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Security;

use CarlosChininin\App\Domain\Model\AuthUser\AuthUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class Security
{
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    private ?AuthUser $user = null;
    private ?array $auths = null;
    private ?string $menuRoute = null;
    private ?bool $isSuperAdmin = null;

    public function __construct(
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function user(): AuthUser
    {
        if (null === $this->user) {
            $this->user = $this->tokenStorage->getToken()->getUser();
        }

        return $this->user;
    }

    public function auths(): array
    {
        if (null !== $this->auths) {
            return $this->auths;
        }

        $auths = [];
        foreach ($this->user()->authRoles() as $authRole) {
            foreach ($authRole->permissions() as $permission) {
                $attributes = $permission->attributes();
                if (isset($auths[$permission->menu()])) {
                    $attributes = array_unique(array_merge($attributes, $auths[$permission->menu()]));
                }
                $auths[$permission->menu()] = $attributes;
            }
        }

        return $this->auths = $auths;
    }

    public function isSuperAdmin(): bool
    {
        if (null === $this->isSuperAdmin) {
            $this->isSuperAdmin = self::verifyRole(self::ROLE_SUPER_ADMIN, $this->user());
        }

        return $this->isSuperAdmin;
    }

    /** @param Permission[] $permissions */
    public function checkGrantedAccess(array $permissions, string $menuRoute, ?object $entity = null): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $this->menuRoute = $menuRoute;
        $auths = $this->auths();

        if (!isset($auths[$menuRoute])) {
            return false;
        }

        if (\in_array('master', $auths[$this->menuRoute], true)) {
            return true;
        }

        foreach ($permissions as $permission) {
            if ((\in_array($permission->value, $auths[$this->menuRoute], true) && $this->isOwner($entity))
                || \in_array($permission->value.'_all', $auths[$this->menuRoute], true)
            ) {
                return true;
            }
        }

        return false;
    }

    public static function verifyRole(string $role, AuthUser $user): bool
    {
        foreach ($user->authRoles() as $authRole) {
            if ($role === $authRole->role()) {
                return true;
            }
        }

        return false;
    }

    public function denyAccessUnlessGranted(array $permissions, string $menuRoute, ?object $entity = null, string $message = 'Acceso denegado...'): void
    {
        if (!$this->checkGrantedAccess($permissions, $menuRoute, $entity)) {
            $exception = new AccessDeniedException($message);
            $exception->setAttributes([$permissions]);
            $exception->setSubject($menuRoute);

            throw $exception;
        }
    }

    public function isOwner(?object $entity): bool
    {
        if (null === $entity) {
            return false;
        }

        if (!method_exists($entity, 'owner')) {
            return false;
        }

        return $entity->owner()?->getUserIdentifier() === $this->user()->getUserIdentifier();
    }
}
