<?php

declare(strict_types=1);

namespace CarlosChininin\App\Infrastructure\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private string $targetDirectory;
    private string $path = '';

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, string $path = ''): string
    {
        $extension = $file->getClientOriginalExtension();
        $secure = sha1(uniqid((string) mt_rand(), true)).'.'.$extension;
        $this->path = $path;

        try {
            $file->move($this->getTargetDirectory(), $secure);
        } catch (FileException) {
        }

        return $secure;
    }

    public function remove(?string $nombre): void
    {
        if (null === $nombre || '' === trim($nombre)) {
            return;
        }

        $file = $this->getTargetDirectory().$nombre;

        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function removePath(?string $filePath): void
    {
        if (null === $filePath || '' === trim($filePath)) {
            return;
        }

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory.$this->path;
    }
}
