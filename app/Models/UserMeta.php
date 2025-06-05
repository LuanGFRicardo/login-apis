<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserMeta extends Authenticatable
{
    protected $table = 'users_meta';

    protected $fillable = [
        'provider_id',
        'name',
        'email',
        'nickname',
        'avatar',
        'token',
        'refresh_token',
        'expires_in',
        'raw_data',
    ];

    protected $casts = [
        'raw_data' => 'array',
    ];
}
