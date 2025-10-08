<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CompanyInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'email',
        'role',
        'token',
        'expires_at',
        'accepted_at',
        'accepted_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function acceptedBy()
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    // Méthodes utilitaires
    public static function createInvitation($companyId, $email, $role = 'member')
    {
        return self::create([
            'company_id' => $companyId,
            'email' => $email,
            'role' => $role,
            'token' => Str::random(40),
            'expires_at' => now()->addDays(7), // Expire dans 7 jours
        ]);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isAccepted()
    {
        return !is_null($this->accepted_at);
    }

    public function accept(User $user)
    {
        $this->update([
            'accepted_at' => now(),
            'accepted_by' => $user->id,
        ]);
    }

    public function getInvitationUrlAttribute()
    {
        return route('invitations.accept', ['token' => $this->token]);
    }
}