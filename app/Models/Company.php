<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'website',
        'logo',
        'plan',
        'monthly_price',
        'user_limit',
        'max_users',
        'status',
        'trial_ends_at',
        'subscription_ends_at',
        'admin_id',
        'stripe_customer_id',
        'stripe_subscription_id',
        'stripe_price_id',
    ];

    protected $casts = [
        'trial_ends_at' => 'date',
        'subscription_ends_at' => 'date',
        'monthly_price' => 'decimal:2',
        'user_limit' => 'integer',
    ];

    // Relations
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function invitations()
    {
        return $this->hasMany(CompanyInvitation::class);
    }

    // Méthodes utilitaires
    public function getPlanDetailsAttribute()
    {
        return [
            'starter' => [
                'name' => 'Starter',
                'price' => 399,
                'users' => 10,
                'description' => 'Parfait pour les petites équipes'
            ],
            'growth' => [
                'name' => 'Growth',
                'price' => 599,
                'users' => 20,
                'description' => 'Idéal pour les équipes en croissance'
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => 999,
                'users' => -1, // Illimité
                'description' => 'Pour les grandes organisations'
            ]
        ][$this->plan] ?? null;
    }

    public function isOnTrial()
    {
        return $this->status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isActive()
    {
        return $this->status === 'active' && 
               (!$this->subscription_ends_at || $this->subscription_ends_at->isFuture());
    }

    public function canAddUser()
    {
        if ($this->plan === 'enterprise') {
            return true; // Utilisateurs illimités
        }
        
        return $this->users()->count() < $this->user_limit;
    }

    public function getRemainingUserSlotsAttribute()
    {
        if ($this->plan === 'enterprise') {
            return -1; // Illimité
        }
        
        return max(0, $this->user_limit - $this->users()->count());
    }
}