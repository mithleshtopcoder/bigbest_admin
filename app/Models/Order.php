<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\InvoiceService;
use App\Models\User;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
{
    parent::boot();

    static::updated(function ($order) {

        // 1️⃣ Check if status changed
        if ($order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;

            if ($oldStatus !== $newStatus) {
                try {
                    // Call your existing NotificationController method
                    app(\App\Http\Controllers\Api\NotificationController::class)
                        ->createNotification(
                            $order->customer_id,
                            'order',
                            'Order Status Updated',
                            "Your order #{$order->order_number} status changed from {$oldStatus} to {$newStatus}.",
                            [
                                'order_id' => $order->id,
                                'old_status' => $oldStatus,
                                'new_status' => $newStatus
                            ]
                        );
                } catch (\Exception $e) {
                    \Log::error('Failed to send order status notification: ' . $e->getMessage());
                }
            }
        }

        // 2️⃣ Existing invoice generation when delivered
        if ($order->isDirty('status') && $order->status === 'delivered' && !$order->file_name) {
            try {
                $invoiceService = new \App\Services\InvoiceService();
                $invoiceService->dispatchGenerateInvoice($order, true);
            } catch (\Exception $e) {
                \Log::error('Failed to dispatch invoice generation job on order status update: ' . $e->getMessage());
            }
        }
    });
}


    protected $fillable = [
        'order_number',
        'customer_id',
        'store_id',
        'delivery_address_id',
        'order_source',
        'created_by',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'tax_amount',
        'shipping_charge',
        'discount_amount',
        'coupon_id',
        'vendor_id',
        'loyalty_points_used',
        'wallet_amount_used',
        'total_amount',
        'delivery_instructions',
        'delivery_date',
        'delivery_time_slot',
        'tracking_number',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
        'total_items',
        'file_name',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'shipping_charge' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'loyalty_points_used' => 'decimal:2',
            'wallet_amount_used' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'delivery_date' => 'date',
            'confirmed_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'total_items' => 'integer',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(CustomerAddress::class, 'delivery_address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function vendor()
{
    return $this->belongsTo(User::class, 'vendor_id', 'vendor_id'); 
    
}

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeOnline($query)
{
    return $query->where('order_source', 'online');
}
}