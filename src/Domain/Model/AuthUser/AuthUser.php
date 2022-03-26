<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Domain\Model\AuthUser;

use CarlosChininin\App\Domain\Model\AuthRole\AuthRole;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AuthUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    /** @return Collection|AuthRole[] */
    abstract public function authRoles(): Collection|array;
}
