<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    protected $table = 'options';

    protected $fillable = [
        'option_master_id',
        'name',
        'value',
        'display_image',
        'default',
        'status',
    ];

    /**
     * Option belongs to Option Master
     */
    public function optionMaster(): BelongsTo
    {
        return $this->belongsTo(OptionMaster::class);
    }
}