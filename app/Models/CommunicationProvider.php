<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunicationProvider extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'channel', // sms | whatsapp | email | etc
        'provider_name',
        'base_url',
        'api_key',
        'api_secret',
        'sender_id',
        'route',
        'default_template_id',
        'meta',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'is_active' => 'boolean',
        ];
    }
}