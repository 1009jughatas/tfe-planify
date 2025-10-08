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
        'company_id',
        'position',
        'department',
        'is_active'
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

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isCompanyAdmin()
    {
        return $this->role === 'company_admin';
    }

    public function isMember()
    {
        return $this->role === 'member';
    }

    public function is_premium()
    {
        // Dans le nouveau système, un utilisateur est "premium" s'il appartient à une entreprise active
        return $this->company && $this->company->isActive();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function participatingProjects()
    {
        return $this->belongsToMany(Project::class, 'project_user');
    }

    public function getCompanyProjectsAttribute()
    {
        return $this->company ? $this->company->projects : collect();
    }

    public function getAssignedTasksAttribute()
    {
        return $this->company ? 
            Task::where('company_id', $this->company_id)
                ->where('assigned_to', $this->id)
                ->get() : 
            collect();
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
