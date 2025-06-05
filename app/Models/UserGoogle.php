<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserGoogle extends Authenticatable
{
    protected $table = 'users_google';

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'nickname',
        'email_verified',
        'locale',
        'hd',
        'given_name',
        'family_name',
        'profile_url',
        'updated_at_google',
        'gender',
        'birthdate',
        'phone_number',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
