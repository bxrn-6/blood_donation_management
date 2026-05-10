<?php

namespace App\Models;

use App\Models\BloodInventory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'blood_type',
        'quantity_donated',
        'donation_date',
        'status',
        'screening_result',
        'notes',
    ];

    protected $casts = [
        'donation_date' => 'date',
    ];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($donation) {
            // Update donor's last donation date
            $donor = $donation->donor;
            $donor->last_donation_date = $donation->donation_date;
            $donor->save();

            // Add to blood inventory
            BloodInventory::create([
                'blood_type' => $donation->blood_type,
                'quantity' => $donation->quantity_donated,
                'donation_date' => $donation->donation_date,
                'expiration_date' => $donation->donation_date->addDays(42), // Blood expires after 42 days
                'status' => 'Available',
            ]);
        });
    }
}
