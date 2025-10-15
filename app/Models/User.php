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
        'is_active',
        'is_premium',
        'stripe_customer_id',
        'stripe_subscription_id',
        'permissions'
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
            'is_premium' => 'boolean',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'permissions' => 'array',
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

    public function isAdminEntreprise()
    {
        return $this->role === 'admin_entreprise';
    }

    public function isUserEntreprise()
    {
        return $this->role === 'user_entreprise';
    }

    public function isUserIndependant()
    {
        return $this->role === 'user_independant' && !$this->company_id;
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission)
    {
        // Les administrateurs d'entreprise ont tous les droits
        if ($this->isAdminEntreprise()) {
            return true;
        }

        // Les utilisateurs indépendants ont tous les droits sur leurs propres données
        if ($this->isUserIndependant()) {
            return true;
        }

        // Vérifier les permissions pour les employés d'entreprise
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    /**
     * Grant a permission to the user
     */
    public function grantPermission($permission)
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->update(['permissions' => $permissions]);
        }
    }

    /**
     * Revoke a permission from the user
     */
    public function revokePermission($permission)
    {
        $permissions = $this->permissions ?? [];
        $permissions = array_values(array_filter($permissions, function($p) use ($permission) {
            return $p !== $permission;
        }));
        $this->update(['permissions' => $permissions]);
    }

    /**
     * Get available permissions for enterprise users
     */
    public static function getAvailablePermissions()
    {
        return [
            'export_pdf' => 'Exporter des fichiers PDF',
            'create_projects' => 'Créer des projets',
            'manage_tasks' => 'Gérer les tâches',
            'invite_users' => 'Inviter des utilisateurs',
            'view_analytics' => 'Voir les analyses',
        ];
    }

    /**
     * Check if user can export PDF
     */
    public function canExportPdf()
    {
        return $this->hasPermission('export_pdf');
    }

    public function isPartOfCompany()
    {
        return $this->company_id !== null;
    }

    // Méthodes de compatibilité avec l'ancien système
    public function isCompanyAdmin()
    {
        return $this->isAdminEntreprise();
    }

    public function isEmploye()
    {
        return $this->isUserEntreprise();
    }

    public function isMember()
    {
        return $this->isUserIndependant();
    }

    public function isIndependent()
    {
        return $this->isUserIndependant();
    }

    public function is_premium()
    {
        // Un utilisateur est premium s'il a payé l'abonnement Premium
        return $this && (bool) $this->getAttribute('is_premium');
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

    /**
     * Relation avec les tickets de support
     */
    public function ticketSupports()
    {
        return $this->hasMany(TicketSupport::class);
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
