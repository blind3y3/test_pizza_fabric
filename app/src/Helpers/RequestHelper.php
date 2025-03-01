<?php

declare(strict_types=1);

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Request;

class RequestHelper
{
    public static function extractOrderId(array $vars): int
    {
        return (int)$vars['order_id'];
    }

    public static function extractItems(Request $request): array
    {
        $data = json_decode($request->getContent(), true);
        return $data['items'] ?? [];
    }
}