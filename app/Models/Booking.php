<?php

namespace App\Models;

use App\Models\User;
use App\Models\Addon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'customer_name',
        'email',
        'phone',
        'package',
        'package_price',
        'total_amount',
        'event_date',
        'event_location',
        'notes',
        'status',

        // Payment information
        'payment_method',
        'payment_status',
        'paid_at',
        'payment_reference',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'package_price' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class, 'booking_addon')
            ->withPivot([
                'addon_name',
                'price',
                'quantity',
            ])
            ->withTimestamps();
    }
}
