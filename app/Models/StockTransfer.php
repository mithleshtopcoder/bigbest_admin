<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transfer_number',
        'from_store_id',
        'to_store_id',
        'status',
        'notes',
        'rejection_reason',
        'transfer_date',
        'expected_delivery_date',
        'received_date',
        'requested_by',
        'approved_by',
        'approved_at',
        'received_by',
        'total_items',
        'total_quantity',
    ];

    protected function casts(): array
    {
        return [
            'transfer_date' => 'date',
            'expected_delivery_date' => 'date',
            'received_date' => 'date',
            'approved_at' => 'datetime',
            'total_items' => 'integer',
            'total_quantity' => 'integer',
        ];
    }

    public function fromStore()
    {
        return $this->belongsTo(Store::class, 'from_store_id');
    }

    public function toStore()
    {
        return $this->belongsTo(Store::class, 'to_store_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function items()
    {
        return $this->hasMany(StockTransferItem::class);
    }
}
