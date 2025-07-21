<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends AbstractController
{
    public function denyAccessUnlessAuthorization(Request $request): void
    {
        $authorization = $this->authorization($request);
        if (!$authorization['status']) {
            throw new \InvalidArgumentException($authorization['message']);
        }
    }

    public function response(array $data, bool $status = true): Response
    {
        return new JsonResponse(array_merge(['status' => $status], $data));
    }

    protected function authorization(Request $request): array
    {
        if (!$request->headers->has('Authorization') || mb_strpos($request->headers->get('Authorization'), 'Bearer ') !== 0) {
            return ['status' => false, 'message' => 'No tiene autorización'];
        }

        $token = mb_substr($request->headers->get('Authorization'), 7);
        if ($token !== $this->getParameter('app.token')) {
            return ['status' => false, 'message' => 'Token no válido'];
        }

        return ['status' => true];
    }
}
