<?php

declare(strict_types=1);

namespace CarlosChininin\App\Domain\Model\AttachedFile\Event;

use CarlosChininin\App\Domain\Model\AttachedFile\AttachedFile;
use Symfony\Contracts\EventDispatcher\Event;

class AttachedFilePersistEvent extends Event
{
    public const NAME = 'attached_file.persist';

    private AttachedFile $attachedFile;

    public function __construct(AttachedFile $attachedFile)
    {
        $this->attachedFile = $attachedFile;
    }

    public function attachedFile(): AttachedFile
    {
        return $this->attachedFile;
    }
}
