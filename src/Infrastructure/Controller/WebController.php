<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Controller;

use CarlosChininin\Util\Error\Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class WebController extends AbstractController
{
    /**
     * @param Error[] $errors
     */
    protected function addErrors(array $errors): void
    {
        foreach ($errors as $error) {
            $this->addFlash('danger', $error->message());
        }
    }
}
