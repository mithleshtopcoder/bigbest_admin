<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedLoginHistory extends Model
{
    protected $table = 'failed_login_history';

    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'device',
        'platform',
        'reason',
        'attempted_at',
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
