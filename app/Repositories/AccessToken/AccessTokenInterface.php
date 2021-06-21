<?php

namespace App\Repositories\AccessToken;

interface AccessTokenInterface
{
    public function create(array $attributes);

    public function deleteExpiredAccessTokens();
}
