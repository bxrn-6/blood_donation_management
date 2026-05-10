<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'gender',
        'age',
        'blood_type',
        'contact_number',
        'address',
        'availability_status',
        'last_donation_date',
    ];

    protected $casts = [
        'last_donation_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function donorMatches(): HasMany
    {
        return $this->hasMany(DonorMatch::class);
    }

    public function isAvailable(): bool
    {
        return $this->availability_status === 'available';
    }

    public function canDonate(): bool
    {
        if (!$this->isAvailable()) {
            return false;
        }

        if ($this->last_donation_date) {
            $daysSinceLastDonation = $this->last_donation_date->diffInDays(now());
            return $daysSinceLastDonation >= 56; // 8 weeks minimum between donations
        }

        return true;
    }
}
