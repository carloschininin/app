<?php

declare(strict_types=1);

namespace CarlosChininin\App\Infrastructure\Twig\Extension;

use CarlosChininin\App\Domain\Model\AttachedFile\AttachedFile;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AttachedFileExtension extends AbstractExtension
{
    public function __construct(private readonly string $attachmentDirectory)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('attached_file_webpath', [$this, 'webpath']),
        ];
    }

    public function webpath(AttachedFile $file): string
    {
        return $this->attachmentDirectory.'/'.$file->path();
    }
}
