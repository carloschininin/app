<?php

declare(strict_types=1);

namespace CarlosChininin\App\Domain\Model\AttachedFile;

use Symfony\Component\HttpFoundation\File\File;

class AttachedFile implements AttachedFileInterface
{
    protected ?int $id = null;

    protected string $name;

    protected string $secure;

    protected ?string $folder = null;

    protected ?File $file = null;

    protected ?string $previousPath = null;

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function secure(): string
    {
        return $this->secure;
    }

    public function setSecure(string $secure): void
    {
        $this->secure = $secure;
    }

    public function folder(): ?string
    {
        return $this->folder;
    }

    public function setFolder(?string $folder): void
    {
        $this->folder = $folder;
    }

    public function file(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): void
    {
        $this->file = $file;
    }

    public function previousPath(): ?string
    {
        return $this->previousPath;
    }

    public function setPreviousPath(?string $previousPath): void
    {
        $this->previousPath = $previousPath;
    }

    public function path(): string
    {
        return $this->folder().'/'.$this->secure();
    }
}
