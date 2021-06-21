<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $table = 'access_tokens';
    protected $hidden = [
        'token'
    ];

    protected $fillable = [
        'user_id',
        'ip',
        'device',
        'token',
        'user_agent',
        'expires_at',
        'is_remembered',
    ];

    protected static $_rules = [
        'user_id' => 'required|numeric',
        'ip' => 'nullable',
        'device' => 'nullable',
        'token' => 'required',
        'user_agent' => 'nullable',
    ];
}
