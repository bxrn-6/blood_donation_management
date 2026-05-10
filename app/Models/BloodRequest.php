<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'hospital_id',
        'hospital_name',
        'blood_type_needed',
        'quantity_requested',
        'urgency_level',
        'request_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'request_date' => 'date',
    ];

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hospital_id');
    }

    public function donorMatches(): HasMany
    {
        return $this->hasMany(DonorMatch::class);
    }

    public static function generateRequestId(): string
    {
        $latest = self::orderBy('id', 'desc')->first();
        $number = $latest ? intval(substr($latest->request_id, -6)) + 1 : 1;
        return 'REQ' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    public function isUrgent(): bool
    {
        return in_array($this->urgency_level, ['high', 'critical']);
    }

    public function canBeFulfilled(): bool
    {
        $availableQuantity = BloodInventory::getAvailableByBloodType($this->blood_type_needed);
        return $availableQuantity >= $this->quantity_requested;
    }

    public function getCompatibleDonors()
    {
        return Donor::where('blood_type', $this->blood_type_needed)
            ->where('availability_status', 'available')
            ->where(function ($query) {
                $query->whereNull('last_donation_date')
                    ->orWhereRaw('DATEDIFF(CURDATE(), last_donation_date) >= 56');
            })
            ->get();
    }
}
