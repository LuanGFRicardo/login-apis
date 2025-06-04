<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMicrosoft extends Model
{
    use HasFactory;

    protected $table = 'users_microsoft';

    protected $fillable = [
        'microsoft_id',
        'display_name',
        'given_name',
        'surname',
        'user_principal_name',
        'mail',
        'mobile_phone',
        'business_phones',
        'job_title',
        'company_name',
        'office_location',
        'preferred_language',
        'microsoft_access_token',
        'microsoft_refresh_token',
        'microsoft_token_expires_at',
        'microsoft_avatar',
    ];

    protected $casts = [
        'business_phones' => 'array',
        'microsoft_token_expires_at' => 'datetime',
    ];
}
