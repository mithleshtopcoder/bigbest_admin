<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SupportTicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'replied_by_type',
        'replied_by_id',
        'message',
        'attachments',
        'is_internal',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_internal' => 'boolean',
    ];

    /**
     * Get the ticket this reply belongs to
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    /**
     * Get the customer who replied (if replied by customer)
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'replied_by_id');
    }

    /**
     * Get the admin/user who replied (if replied by admin)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by_id');
    }

    /**
     * Get the replier (customer or admin) - dynamic relationship
     */
    public function replier()
    {
        if ($this->replied_by_type === 'customer') {
            return $this->belongsTo(Customer::class, 'replied_by_id');
        } else {
            return $this->belongsTo(User::class, 'replied_by_id');
        }
    }
}
