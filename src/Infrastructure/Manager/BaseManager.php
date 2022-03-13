<?php

declare(strict_types=1);

namespace CarlosChininin\App\Infrastructure\Manager;

use CarlosChininin\Util\Error\Error;

abstract class BaseManager
{
    private array $errors;

    public function addError(Error $error): void
    {
        $this->errors[] = $error;
    }

    public function addErrors(array $errors): void
    {
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    public function errors(): array
    {
        return $this->errors;
    }
}