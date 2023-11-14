<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Security\Menu;

use CarlosChininin\App\Domain\Model\AuthMenu\AuthMenu;
use CarlosChininin\App\Domain\Model\AuthRole\AuthRole;
use CarlosChininin\App\Domain\Model\AuthUser\AuthUser;
use CarlosChininin\App\Infrastructure\Security\Security;

class MenuBuilder
{
    public const SUBLEVEL = 'submenu';

    public function __construct(private readonly Security $security)
    {
    }

    public function execute(array $menus, string $menuSelected): array
    {
        $userMenus = $this->security->isSuperAdmin()
            ? $this->allMenupaths($menus)
            : $this->userMenuPaths($this->security->user());

        $buildMenus = $this->buildMenus($menus, $userMenus);
        $this->selectedMenus($buildMenus, $menuSelected);

        return $buildMenus;
    }

    /** @param AuthMenu[] $menus */
    public function allMenupaths(array $menus): array
    {
        $menuPaths = [];
        foreach ($menus as $menu) {
            if (null !== $menu->getParent()) {
                $menuPaths[] = $menu->getRoute();
            }
        }

        return $menuPaths;
    }

    public function userMenuPaths(AuthUser $user): array
    {
        $menuPaths = [];
        /** @var AuthRole $authRole */
        foreach ($user->authRoles() as $authRole) {
            foreach ($authRole->permissions() as $permission) {
                $menuPaths[] = $permission->menu();
            }
        }

        return $menuPaths;
    }

    /** @param AuthMenu[] $menus */
    public function buildMenus(array $menus, array $userPaths): array
    {
        $sections = [];
        foreach ($userPaths as $userPath) {
            $newMenus = $this->menuParents($menus, $userPath);
            $level = null;
            $menuLevel = [];
            foreach ($newMenus as $newMenu) {
                if (null === $level) {
                    $level = [];
                    $menuLevel = &$level;
                }

                $level[$newMenu['name']] = $newMenu;
                $level[$newMenu['name']][static::SUBLEVEL] = [];
                $level = &$level[$newMenu['name']][static::SUBLEVEL];
            }

            $this->mergeMenus($sections, $menuLevel);
            unset($level);
        }

        return $sections;
    }

    /** @param AuthMenu[] $menus */
    protected function menuParents(array $menus, string $path): array
    {
        $parents = [];
        foreach ($menus as $menu) {
            if ($menu->getRoute() === $path) {
                $this->getParents($menus, $menu->getId(), $parents);

                return $parents;
            }
        }

        return $parents;
    }

    /** @param AuthMenu[] $menus */
    protected function getParents(array $menus, int $menuId, array &$parents): void
    {
        foreach ($menus as $menu) {
            if ($menu->getId() === $menuId) {
                $parentId = $menu->getParent()?->getId();
                if (null !== $parentId) {
                    $this->getParents($menus, $parentId, $parents);
                }
                $parents[] = $menu->toArray();

                break;
            }
        }
    }

    protected function mergeMenus(array &$sections, array &$menuLevel): void
    {
        if (0 === \count($sections) && 0 !== \count($menuLevel)) {
            $sections = $menuLevel;

            return;
        }

        foreach ($sections as $key => $section) {
            if (isset($menuLevel[$key])) {
                // $sections[$key] es el original section
                $this->mergeMenus($sections[$key][static::SUBLEVEL], $menuLevel[$key][static::SUBLEVEL]);

                return;
            }
        }

        $sections = array_merge($sections, $menuLevel);
    }

    public function selectedMenus(array &$menus, string $menuSelected): bool
    {
        foreach ($menus as $key => $menu) {
            // $menus[$key] es el original menu
            if ($menu['route'] === $menuSelected || $this->selectedMenus($menus[$key][static::SUBLEVEL], $menuSelected)) {
                $menus[$key]['selected'] = true;

                return true;
            }
        }

        return false;
    }
}
