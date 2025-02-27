<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController
{
    public function index(): JsonResponse
    {
        return new JsonResponse(['success' => true]);
    }
}