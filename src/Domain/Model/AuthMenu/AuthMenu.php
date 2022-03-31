<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Domain\Model\AuthMenu;

abstract class AuthMenu
{
    abstract public function getId(): ?int;

    abstract public function getName(): ?string;

    abstract public function getRoute(): ?string;

    abstract public function getIcon(): ?string;

    abstract public function getRanking(): ?int;

    abstract public function getParent(): ?self;

    abstract public function getBadge(): ?string;

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'route' => $this->getRoute(),
            'icon' => $this->getIcon(),
            'ranking' => $this->getRanking(),
            'badge' => $this->getBadge(),
        ];
    }
}
