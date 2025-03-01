<?php

declare(strict_types=1);

namespace Modules\Auth\Service;


use App\Modules\Auth\Exception\UnauthorizedException;

class AuthService
{
    /**
     * @throws UnauthorizedException
     */
    public function checkRequestHaveAuthHeader(?string $header): void
    {
        if ($header !== getenv('X_AUTH_KEY')) {
            throw new UnauthorizedException();
        };
    }
}