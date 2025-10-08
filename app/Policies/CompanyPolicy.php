<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    /**
     * Determine if the user can view any companies.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine if the user can view the company.
     */
    public function view(User $user, Company $company): bool
    {
        // Super admin peut voir toutes les entreprises
        if ($user->isSuperAdmin()) {
            return true;
        }

        // L'admin de l'entreprise peut voir son entreprise
        if ($user->isCompanyAdmin() && $user->company_id === $company->id) {
            return true;
        }

        // Les membres peuvent voir leur entreprise
        if ($user->company_id === $company->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create companies.
     */
    public function create(User $user): bool
    {
        // Seuls les super admins peuvent créer des entreprises
        return $user->isSuperAdmin();
    }

    /**
     * Determine if the user can update the company.
     */
    public function update(User $user, Company $company): bool
    {
        // Super admin peut modifier toutes les entreprises
        if ($user->isSuperAdmin()) {
            return true;
        }

        // L'admin de l'entreprise peut modifier son entreprise
        return $user->isCompanyAdmin() && $user->company_id === $company->id;
    }

    /**
     * Determine if the user can delete the company.
     */
    public function delete(User $user, Company $company): bool
    {
        // Seuls les super admins peuvent supprimer des entreprises
        return $user->isSuperAdmin();
    }

    /**
     * Determine if the user can manage company users.
     */
    public function manageUsers(User $user, Company $company): bool
    {
        // Super admin peut gérer tous les utilisateurs
        if ($user->isSuperAdmin()) {
            return true;
        }

        // L'admin de l'entreprise peut gérer les utilisateurs de son entreprise
        return $user->isCompanyAdmin() && $user->company_id === $company->id;
    }

    /**
     * Determine if the user can invite users to the company.
     */
    public function inviteUsers(User $user, Company $company): bool
    {
        // Super admin peut inviter dans toutes les entreprises
        if ($user->isSuperAdmin()) {
            return true;
        }

        // L'admin de l'entreprise peut inviter des utilisateurs
        return $user->isCompanyAdmin() && 
               $user->company_id === $company->id && 
               $company->canAddUser();
    }

    /**
     * Determine if the user can manage company billing.
     */
    public function manageBilling(User $user, Company $company): bool
    {
        // Super admin peut gérer la facturation de toutes les entreprises
        if ($user->isSuperAdmin()) {
            return true;
        }

        // L'admin de l'entreprise peut gérer la facturation de son entreprise
        return $user->isCompanyAdmin() && $user->company_id === $company->id;
    }
}