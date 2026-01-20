<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OptionMaster extends Model
{
    use HasFactory;

    protected $table = 'option_masters';

    protected $fillable = [
        'name',
    ];

    /**
     * Option Master has many options
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }
}