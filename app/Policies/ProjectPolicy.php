<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine if the user can view any projects.
     */
    public function viewAny(User $user): bool
    {
        // Super admin peut voir tous les projets
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Les utilisateurs indépendants peuvent voir leurs projets
        if ($user->isUserIndependant()) {
            return true;
        }

        // Les utilisateurs d'entreprise peuvent voir les projets de leur entreprise
        return $user->company_id !== null;
    }

    /**
     * Determine if the user can view the project.
     */
    public function view(User $user, Project $project): bool
    {
        // Super admin peut voir tous les projets
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Les utilisateurs indépendants peuvent voir leurs propres projets
        if ($user->isUserIndependant()) {
            // L'auteur peut voir son projet
            if ($project->author_id === $user->id) {
                return true;
            }
            // Les participants peuvent voir les projets auxquels ils participent
            return $project->participants->contains($user->id);
        }

        // Cloisonnement par entreprise pour les utilisateurs d'entreprise
        if ($user->company_id !== $project->company_id) {
            return false;
        }

        // L'auteur peut voir son projet
        if ($project->author_id === $user->id) {
            return true;
        }

        // Les participants peuvent voir les projets auxquels ils participent
        return $project->participants->contains($user->id);
    }

    /**
     * Determine if the user can create projects.
     */
    public function create(User $user): bool
    {
        // Super admin peut créer des projets partout
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Les utilisateurs indépendants peuvent créer des projets
        if ($user->isUserIndependant()) {
            return true;
        }

        // Les utilisateurs d'entreprise doivent appartenir à une entreprise active
        return $user->company_id !== null && $user->company && $user->company->isActive();
    }

    /**
     * Determine if the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        // Super admin peut modifier tous les projets
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Les utilisateurs indépendants peuvent modifier leurs propres projets
        if ($user->isUserIndependant()) {
            return $project->author_id === $user->id;
        }

        // Cloisonnement par entreprise pour les utilisateurs d'entreprise
        if ($user->company_id !== $project->company_id) {
            return false;
        }

        // L'admin de l'entreprise peut modifier tous les projets de son entreprise
        if ($user->isCompanyAdmin()) {
            return true;
        }

        // L'auteur peut modifier son projet
        return $project->author_id === $user->id;
    }

    /**
     * Determine if the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        // Super admin peut supprimer tous les projets
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Les utilisateurs indépendants peuvent supprimer leurs propres projets
        if ($user->isUserIndependant()) {
            return $project->author_id === $user->id;
        }

        // Cloisonnement par entreprise pour les utilisateurs d'entreprise
        if ($user->company_id !== $project->company_id) {
            return false;
        }

        // L'admin de l'entreprise peut supprimer tous les projets de son entreprise
        if ($user->isCompanyAdmin()) {
            return true;
        }

        // L'auteur peut supprimer son projet
        return $project->author_id === $user->id;
    }

    /**
     * Determine if the user can invite collaborators to the project.
     */
    public function inviteCollaborators(User $user, Project $project): bool
    {
        // Super admin peut toujours inviter
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Cloisonnement par entreprise
        if ($user->company_id !== $project->company_id) {
            return false;
        }

        // L'admin de l'entreprise peut inviter dans tous les projets
        if ($user->isCompanyAdmin()) {
            return true;
        }

        // L'auteur peut inviter des collaborateurs de la même entreprise
        return $project->author_id === $user->id;
    }

    /**
     * Determine if the user can access project statistics.
     */
    public function viewStatistics(User $user, Project $project): bool
    {
        // Super admin a toujours accès
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Cloisonnement par entreprise
        if ($user->company_id !== $project->company_id) {
            return false;
        }

        // L'auteur ou participant peut voir les statistiques
        return $project->author_id === $user->id || $project->participants->contains($user->id);
    }

    /**
     * Determine if the user can access Kanban board.
     */
    public function viewKanban(User $user, Project $project): bool
    {
        // Super admin a toujours accès
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Cloisonnement par entreprise
        if ($user->company_id !== $project->company_id) {
            return false;
        }

        // L'auteur ou participant peut voir le Kanban
        return $project->author_id === $user->id || $project->participants->contains($user->id);
    }

    /**
     * Determine if the user can upload files to the project.
     */
    public function uploadFiles(User $user, Project $project): bool
    {
        // Super admin peut toujours uploader
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Cloisonnement par entreprise
        if ($user->company_id !== $project->company_id) {
            return false;
        }

        // L'auteur ou participant peut uploader
        return $project->author_id === $user->id || $project->participants->contains($user->id);
    }

    /**
     * Determine if the user can export reports.
     */
    public function exportReport(User $user, Project $project): bool
    {
        // Super admin peut toujours exporter
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Cloisonnement par entreprise
        if ($user->company_id !== $project->company_id) {
            return false;
        }

        // L'auteur ou participant peut exporter
        return $project->author_id === $user->id || $project->participants->contains($user->id);
    }
}