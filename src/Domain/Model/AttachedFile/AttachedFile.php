<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Domain\Model\AttachedFile;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachedFile implements AttachedFileInterface
{
    protected ?int $id = null;

    protected string $name;

    protected ?string $secure = null;

    protected ?string $folder = null;

    protected ?UploadedFile $file = null;

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

    public function secure(): ?string
    {
        return $this->secure;
    }

    public function setSecure(?string $secure): void
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

    public function file(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): void
    {
        if (null !== $file) {
            $this->setName(pathinfo($file->getClientOriginalName(), \PATHINFO_FILENAME));
            $this->file = $file;
        }
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
