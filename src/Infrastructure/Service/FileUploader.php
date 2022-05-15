<?php

declare(strict_types=1);

namespace CarlosChininin\App\Infrastructure\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(
        private readonly string $publicDirectory,
        private readonly string $attachmentDirectory
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $secure = sha1(uniqid((string) mt_rand(), true)).'.'.$extension;

        try {
            $file->move($this->getTargetDirectory(), $secure);
        } catch (FileException) {
        }

        return $secure;
    }

    public function remove(?string $filename): void
    {
        if (null === $filename) {
            return;
        }

        $filePath = $this->getTargetDirectory().$filename;

        $this->removePath($filePath);
    }

    public function removePath(?string $filePath): void
    {
        if (null === $filePath) {
            return;
        }

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function getTargetDirectory(): string
    {
        return $this->publicDirectory.$this->attachmentDirectory;
    }
}
