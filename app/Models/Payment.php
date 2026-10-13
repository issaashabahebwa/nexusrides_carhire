<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'amount',
        'method',
        'transaction_id',
        'phone_number',
        'status',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMethodLabelAttribute(): string
    {
        return match($this->method) {
            'mtn_momo'    => 'MTN Mobile Money',
            'airtel_money'=> 'Airtel Money',
            'card'        => 'Card Payment',
            'cash'        => 'Cash',
            default       => ucfirst($this->method),
        };
    }
}
