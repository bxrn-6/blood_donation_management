<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonorMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_request_id',
        'donor_id',
        'status',
        'matched_at',
        'responded_at',
        'response_notes',
    ];

    protected $casts = [
        'matched_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function bloodRequest(): BelongsTo
    {
        return $this->belongsTo(BloodRequest::class);
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public function accept(): void
    {
        $this->status = 'accepted';
        $this->responded_at = now();
        $this->save();
    }

    public function decline(string $notes = null): void
    {
        $this->status = 'declined';
        $this->responded_at = now();
        $this->response_notes = $notes;
        $this->save();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isDeclined(): bool
    {
        return $this->status === 'declined';
    }
}
