<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expense_number',
        'store_id',
        'category_id',
        'payment_method_id',
        'status_id',
        'title',
        'description',
        'amount',
        'expense_date',
        'reference_number',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expense_date' => 'date',
        ];
    }

    /**
     * Expense belongs to a store
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Expense belongs to a category (option)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'category_id');
    }

    /**
     * Expense belongs to a payment method (option)
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'payment_method_id');
    }

    /**
     * Expense belongs to a status (option)
     */
    public function statusOption(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'status_id');
    }

    /**
     * Expense created by a user
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate expense number
     */
    public static function generateExpenseNumber(): string
    {
        $prefix = 'EXP-';
        $lastExpense = self::withTrashed()
            ->where('expense_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastExpense) {
            $lastNumber = (int) substr($lastExpense->expense_number, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
}
