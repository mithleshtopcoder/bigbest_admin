<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Jobs\SendSmsJob;
use Illuminate\Support\Facades\Log;

class SupportTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'customer_id',
        'subject',
        'description',
        'priority',
        'status',
        'assigned_to',
        'assigned_by',
        'assigned_at',
        'resolved_at',
        'closed_at',
        'resolution_notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Generate ticket number on creation
        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = 'TKT-' . strtoupper(Str::random(8));
            }
        });

        // Send SMS on ticket creation
        static::created(function ($ticket) {
            $ticket->sendTicketSms('support_ticket_update'); // you can use a different template for creation if needed
        });

        // Send SMS on ticket update (status change)
        static::updated(function ($ticket) {
            if ($ticket->isDirty('status')) {
                $ticket->sendTicketSms('support_ticket_update');
            }
        });
    }

    /**
     * Send SMS to the customer
     */
    public function sendTicketSms(string $templateKey)
    {
        $customer = $this->customer;

        if ($customer && $customer->phone) {
            try {
                SendSmsJob::dispatch(
                    $templateKey,
                    $customer->phone,
                    [
                        'NAME' => $customer->first_name,
                        'TICKETID' => $this->ticket_number,
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Failed to send support ticket SMS', [
                    'ticket_id' => $this->id,
                    'customer_id' => $customer->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Get the customer that created the ticket
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user assigned to the ticket
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who assigned the ticket
     */
    public function assignedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get all replies for this ticket
     */
    public function replies(): HasMany
    {
        return $this->hasMany(SupportTicketReply::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    /**
     * Get public replies (non-internal)
     */
    public function publicReplies(): HasMany
    {
        return $this->hasMany(SupportTicketReply::class, 'ticket_id')
            ->where('is_internal', false)
            ->orderBy('created_at', 'asc');
    }
}