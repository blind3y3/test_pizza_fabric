<?php

declare(strict_types=1);

namespace Modules\Auth\Service;


use App\Modules\Auth\Exception\UnauthorizedException;

class AuthService
{
    public function checkRequestHaveAuthHeader(?string $header): void
    {
        if ($header !== 'e7fb4515-8265-4964-b8a4-4e7802476676') {
            throw new UnauthorizedException();
        };
    }
}