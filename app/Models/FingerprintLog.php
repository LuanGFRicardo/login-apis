<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FingerprintLog extends Model
{
    protected $fillable = [
        'user_id',
        'fingerprint',
        'ip_address'
    ];
}