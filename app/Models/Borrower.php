<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Borrower extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'contact_number',
        'address',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function utangEntries(): HasMany
    {
        return $this->hasMany(UtangEntry::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalUtangAttribute(): float
    {
        return $this->utangEntries()->sum('amount');
    }

    public function getTotalPaymentsAttribute(): float
    {
        return $this->payments()->sum('amount');
    }

    public function getBalanceAttribute(): float
    {
        return $this->total_utang - $this->total_payments;
    }
}
