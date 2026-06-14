<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_code',
        'total_amount',
        'status',
        'payment_method',
        'midtrans_response',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'midtrans_response' => 'array',
    ];

    /**
     * Get the user who made this payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all payment details (per canteen)
     */
    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }
}
