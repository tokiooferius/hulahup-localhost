<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'canteen_id',
        'order_number',
        'items',
        'total_amount',
        'status',
        'notes',
        'processing_at',
        'completed_at',
    ];

    protected $casts = [
        'items' => 'array',
        'processing_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the canteen this order is from
     */
    public function canteen(): BelongsTo
    {
        return $this->belongsTo(Canteen::class);
    }

    /**
     * Generate unique order number - Extract max from order_number column
     */
    public static function generateOrderNumber()
    {
        // Get the maximum order number, extract numeric part, and increment
        $lastOrderNum = \DB::table('orders')
            ->orderBy('id', 'desc')
            ->first(['order_number']);
        
        if ($lastOrderNum && preg_match('/ORD(\d+)/', $lastOrderNum->order_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        
        return 'ORD' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
