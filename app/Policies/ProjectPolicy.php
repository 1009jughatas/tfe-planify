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
        // Tous les utilisateurs authentifiés peuvent voir la liste des projets
        return true;
    }

    /**
     * Determine if the user can view the project.
     */
    public function view(User $user, Project $project): bool
    {
        // Les admins peuvent voir tous les projets
        if ($user->is_admin()) {
            return true;
        }

        // Les utilisateurs peuvent voir leurs propres projets
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
        // Les admins peuvent créer des projets sans limite
        if ($user->is_admin()) {
            return true;
        }

        // Utilisateurs gratuits : limité à 3 projets
        if (!$user->is_premium) {
            $projectCount = $user->projects()->count();
            return $projectCount < 3;
        }

        // Utilisateurs premium : pas de limite
        return true;
    }

    /**
     * Determine if the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        // Les admins peuvent modifier tous les projets
        if ($user->is_admin()) {
            return true;
        }

        // Seul l'auteur peut modifier son projet
        return $project->author_id === $user->id;
    }

    /**
     * Determine if the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        // Les admins peuvent supprimer tous les projets
        if ($user->is_admin()) {
            return true;
        }

        // Seul l'auteur peut supprimer son projet
        return $project->author_id === $user->id;
    }

    /**
     * Determine if the user can invite collaborators to the project.
     */
    public function inviteCollaborators(User $user, Project $project): bool
    {
        // Les admins peuvent toujours inviter
        if ($user->is_admin()) {
            return true;
        }

        // Seuls les utilisateurs premium peuvent inviter des collaborateurs
        if (!$user->is_premium) {
            return false;
        }

        // L'utilisateur doit être l'auteur du projet
        return $project->author_id === $user->id;
    }

    /**
     * Determine if the user can access project statistics.
     */
    public function viewStatistics(User $user, Project $project): bool
    {
        // Les admins ont toujours accès
        if ($user->is_admin()) {
            return true;
        }

        // Seuls les utilisateurs premium ont accès aux statistiques
        if (!$user->is_premium) {
            return false;
        }

        // L'utilisateur doit être l'auteur ou participant
        return $project->author_id === $user->id || $project->participants->contains($user->id);
    }

    /**
     * Determine if the user can access Kanban board.
     */
    public function viewKanban(User $user, Project $project): bool
    {
        // Les admins ont toujours accès
        if ($user->is_admin()) {
            return true;
        }

        // Seuls les utilisateurs premium ont accès au Kanban
        if (!$user->is_premium) {
            return false;
        }

        // L'utilisateur doit être l'auteur ou participant
        return $project->author_id === $user->id || $project->participants->contains($user->id);
    }

    /**
     * Determine if the user can upload files to the project.
     */
    public function uploadFiles(User $user, Project $project): bool
    {
        // Les admins peuvent toujours uploader
        if ($user->is_admin()) {
            return true;
        }

        // Seuls les utilisateurs premium peuvent uploader des fichiers
        if (!$user->is_premium) {
            return false;
        }

        // L'utilisateur doit être l'auteur ou participant
        return $project->author_id === $user->id || $project->participants->contains($user->id);
    }

    /**
     * Determine if the user can export reports.
     */
    public function exportReport(User $user, Project $project): bool
    {
        // Les admins peuvent toujours exporter
        if ($user->is_admin()) {
            return true;
        }

        // Seuls les utilisateurs premium peuvent exporter
        if (!$user->is_premium) {
            return false;
        }

        // L'utilisateur doit être l'auteur ou participant
        return $project->author_id === $user->id || $project->participants->contains($user->id);
    }
}