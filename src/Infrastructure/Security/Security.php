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

    public function denyAccessUnlessGranted(array $permissions, string $menuRoute, ?object $entity = null, string $message = 'access_denied'): void
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

    public function master(): bool
    {
        return $this->isSuperAdmin() || \in_array(Permission::MASTER, $this->authsMenu(), true);
    }

    public function new(): bool
    {
        return $this->master() || \in_array(Permission::NEW, $this->authsMenu(), true);
    }

    public function list(): bool
    {
        return $this->permissionCheck(Permission::LIST, Permission::LIST_ALL);
    }

    public function show(AuthUser $owner = null): bool
    {
        return $this->permissionCheck(Permission::SHOW, Permission::SHOW_ALL, $owner);
    }

    public function edit(AuthUser $owner = null): bool
    {
        return $this->permissionCheck(Permission::EDIT, Permission::EDIT_ALL, $owner);
    }

    public function enable(AuthUser $owner = null): bool
    {
        return $this->permissionCheck(Permission::ENABLE, Permission::ENABLE_ALL, $owner);
    }

    public function disable(AuthUser $owner = null): bool
    {
        return $this->permissionCheck(Permission::DISABLE, Permission::DISABLE_ALL, $owner);
    }

    public function print(AuthUser $owner = null): bool
    {
        return $this->permissionCheck(Permission::PRINT, Permission::PRINT_ALL, $owner);
    }

    public function report(AuthUser $owner = null): bool
    {
        return $this->permissionCheck(Permission::REPORT, Permission::REPORT_ALL, $owner);
    }

    public function export(AuthUser $owner = null): bool
    {
        return $this->permissionCheck(Permission::EXPORT, Permission::EXPORT_ALL, $owner);
    }

    public function import(AuthUser $owner = null): bool
    {
        return $this->permissionCheck(Permission::IMPORT, Permission::IMPORT_ALL, $owner);
    }

    public function delete(AuthUser $owner = null): bool
    {
        return $this->permissionCheck(Permission::DELETE, Permission::DELETE_ALL, $owner);
    }

    /** @deprecated User functions new, list, edit, etc */
    public function has(string $attribute, ?object $object = null, ?string $menuRoute = null): bool
    {
        $menuRoute = $menuRoute ?? $this->menuRoute;
        $permission = Permission::byValue($attribute);

        return $this->checkGrantedAccess([$permission], $menuRoute, $object);
    }

    private function permissionCheck(Permission $permission, Permission $permissionAll, AuthUser $owner = null): bool
    {
        return
            $this->master() ||
            \in_array($permissionAll->value, $this->authsMenu(), true) ||
            (
                (\in_array($permission->value, $this->authsMenu(), true)) &&
                $this->isOwner($owner)
            );
    }

    private function authsMenu(): array
    {
        return $this->auths[$this->menuRoute] ?? [];
    }
}
