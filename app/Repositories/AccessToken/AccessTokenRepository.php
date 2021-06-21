<?php

namespace App\Repositories\AccessToken;

use App\Models\AccessToken;
use DateTime;
use DateTimeZone;

class AccessTokenRepository implements AccessTokenInterface
{
    private $model;

    public function __construct(AccessToken $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes): AccessToken
    {
        return $this->model->create($attributes);
    }

    public function findByAccessToken($token)
    {
        return $this->model->whereToken($token)->first();
    }

    public function deleteExpiredAccessTokens()
    {
        $now = new DateTime('now', new DateTimeZone('UTC'));
        AccessToken::where('expires_at', '<', $now->format('Y-m-d H:i:s'))
            ->delete();
    }
}
