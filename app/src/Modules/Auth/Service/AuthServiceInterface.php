<?php

namespace Modules\Auth\Service;

interface AuthServiceInterface
{
    public function checkRequestHaveAuthHeader(?string $header): void;
}