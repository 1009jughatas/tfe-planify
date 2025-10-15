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
        'invited_by',
        'position',
        'department',
        'status',
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

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    // Méthodes utilitaires
    public static function createInvitation($companyId, $email, $role = 'user_entreprise', $invitedBy = null)
    {
        return self::create([
            'company_id' => $companyId,
            'email' => $email,
            'role' => $role,
            'invited_by' => $invitedBy,
            'token' => Str::random(60),
            'expires_at' => now()->addDays(7), // Expire dans 7 jours
            'status' => 'pending',
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
            'status' => 'accepted',
        ]);
    }

    public function getInvitationUrlAttribute()
    {
        return route('invitations.accept', ['token' => $this->token]);
    }
}