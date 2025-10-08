<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_premium',
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'author_id');
    }

    public function is_admin()
    {
        return $this->role === 'admin';
    }

    public function is_premium()
    {
        return $this->is_premium === 1 || $this->is_premium === true;
    }

    public function participatingProjects()
    {
        return $this->belongsToMany(Project::class, 'project_user');
    }

    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Get or create user preferences.
     */
    public function getPreferences()
    {
        if (!$this->preferences) {
            return UserPreference::create(['user_id' => $this->id]);
        }
        return $this->preferences;
    }
}
