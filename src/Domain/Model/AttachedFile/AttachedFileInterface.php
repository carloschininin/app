<?php

declare(strict_types=1);

namespace CarlosChininin\App\Domain\Model\AttachedFile;

interface AttachedFileInterface
{
    public function id(): ?int;

    public function name(): string;

    public function secure(): string;

    public function folder(): ?string;
}
