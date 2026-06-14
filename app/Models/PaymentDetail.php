<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'canteen_id',
        'amount_for_canteen',
        'status',
    ];

    protected $casts = [
        'amount_for_canteen' => 'decimal:2',
    ];

    /**
     * Get the payment this detail belongs to
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the canteen this payment is for
     */
    public function canteen()
    {
        return $this->belongsTo(Canteen::class);
    }

    /**
     * Get the settlement for this payment detail
     */
    public function settlement()
    {
        return $this->hasOne(CanteenSettlement::class);
    }
}
