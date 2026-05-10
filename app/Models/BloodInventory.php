<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodInventory extends Model
{
    use HasFactory;

    protected $table = 'blood_inventory';

    protected $fillable = [
        'blood_type',
        'quantity',
        'donation_date',
        'expiration_date',
        'status',
    ];

    protected $casts = [
        'donation_date' => 'date',
        'expiration_date' => 'date',
    ];

    public function isExpired(): bool
    {
        return $this->expiration_date->isPast();
    }

    public function isLow(): bool
    {
        return $this->quantity <= 5; // Consider low stock if 5 or fewer units
    }

    public function updateStatus(): void
    {
        if ($this->isExpired()) {
            $this->status = 'Expired';
        } elseif ($this->isLow()) {
            $this->status = 'Low';
        } else {
            $this->status = 'Available';
        }
        $this->save();
    }

    public static function getAvailableByBloodType(string $bloodType): int
    {
        return self::where('blood_type', $bloodType)
            ->where('status', 'Available')
            ->where('expiration_date', '>', now())
            ->sum('quantity');
    }
}
