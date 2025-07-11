<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Controller;

use CarlosChininin\Util\Error\Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class WebController extends AbstractController
{
    /** @param Error[] $errors */
    protected function addErrors(array $errors): void
    {
        foreach ($errors as $error) {
            $this->addFlash('danger', $error->message());
        }
    }

    protected function messageSuccess(string $message): void
    {
        $this->addFlash('success', $message);
    }

    protected function messageWarning(string $message): void
    {
        $this->addFlash('warning', $message);
    }

    protected function messageError(string $message): void
    {
        $this->addFlash('danger', $message);
    }

    protected function messageInfo(string $message): void
    {
        $this->addFlash('info', $message);
    }
}
