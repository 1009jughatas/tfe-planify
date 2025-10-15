<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine if the user can view any tasks.
     */
    public function viewAny(User $user): bool
    {
        // Super admin peut voir toutes les tâches
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Les utilisateurs indépendants peuvent voir leurs tâches
        if ($user->isUserIndependant()) {
            return true;
        }

        // Les utilisateurs d'entreprise peuvent voir les tâches de leur entreprise
        return $user->company_id !== null;
    }

    /**
     * Determine if the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        // Super admin peut voir toutes les tâches
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Les utilisateurs indépendants peuvent voir leurs tâches
        if ($user->isUserIndependant()) {
            // L'auteur peut voir ses tâches
            if ($task->author_id === $user->id) {
                return true;
            }
            // L'utilisateur assigné peut voir ses tâches
            if ($task->assigned_to === $user->id) {
                return true;
            }
            // Les participants au projet peuvent voir les tâches du projet
            return $task->project->participants->contains($user->id);
        }

        // Cloisonnement par entreprise pour les utilisateurs d'entreprise
        if ($user->company_id !== $task->project->company_id) {
            return false;
        }

        // L'admin de l'entreprise peut voir toutes les tâches de son entreprise
        if ($user->isAdminEntreprise()) {
            return true;
        }

        // L'auteur peut voir ses tâches
        if ($task->author_id === $user->id) {
            return true;
        }

        // L'utilisateur assigné peut voir ses tâches
        if ($task->assigned_to === $user->id) {
            return true;
        }

        // Les participants au projet peuvent voir les tâches du projet
        return $task->project->participants->contains($user->id);
    }

    /**
     * Determine if the user can create tasks.
     */
    public function create(User $user): bool
    {
        // Super admin peut créer des tâches partout
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Les utilisateurs indépendants peuvent créer des tâches
        if ($user->isUserIndependant()) {
            return true;
        }

        // Les utilisateurs d'entreprise doivent appartenir à une entreprise active
        return $user->company_id !== null && $user->company && $user->company->isActive();
    }

    /**
     * Determine if the user can update the task.
     */
    public function update(User $user, Task $task): bool
    {
        // Super admin peut modifier toutes les tâches
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Les utilisateurs indépendants peuvent modifier leurs tâches
        if ($user->isUserIndependant()) {
            // L'auteur peut modifier ses tâches
            if ($task->author_id === $user->id) {
                return true;
            }
            // L'utilisateur assigné peut modifier la tâche
            if ($task->assigned_to === $user->id) {
                return true;
            }
            return false;
        }

        // Cloisonnement par entreprise pour les utilisateurs d'entreprise
        if ($user->company_id !== $task->project->company_id) {
            return false;
        }

        // L'admin de l'entreprise peut modifier toutes les tâches de son entreprise
        if ($user->isCompanyAdmin()) {
            return true;
        }

        // L'auteur peut modifier ses tâches
        if ($task->author_id === $user->id) {
            return true;
        }

        // L'utilisateur assigné peut modifier la tâche (pour utilisateurs de l'entreprise)
        if ($task->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the task.
     */
    public function delete(User $user, Task $task): bool
    {
        // Super admin peut supprimer toutes les tâches
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Les utilisateurs indépendants peuvent supprimer leurs tâches
        if ($user->isUserIndependant()) {
            return $task->author_id === $user->id;
        }

        // Cloisonnement par entreprise pour les utilisateurs d'entreprise
        if ($user->company_id !== $task->project->company_id) {
            return false;
        }

        // L'admin de l'entreprise peut supprimer toutes les tâches de son entreprise
        if ($user->isCompanyAdmin()) {
            return true;
        }

        // Seul l'auteur peut supprimer sa tâche
        return $task->author_id === $user->id;
    }

    /**
     * Determine if the user can change the task status.
     */
    public function updateStatus(User $user, Task $task): bool
    {
        // Super admin peut toujours changer le statut
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Les utilisateurs indépendants peuvent changer le statut de leurs tâches
        if ($user->isUserIndependant()) {
            // L'auteur peut changer le statut de ses tâches
            if ($task->author_id === $user->id) {
                return true;
            }
            // L'utilisateur assigné peut changer le statut de ses tâches
            if ($task->assigned_to === $user->id) {
                return true;
            }
            return false;
        }

        // Cloisonnement par entreprise pour les utilisateurs d'entreprise
        if ($user->company_id !== $task->project->company_id) {
            return false;
        }

        // L'admin de l'entreprise peut changer le statut de toutes les tâches
        if ($user->isCompanyAdmin()) {
            return true;
        }

        // L'auteur peut changer le statut
        if ($task->author_id === $user->id) {
            return true;
        }

        // L'utilisateur assigné peut changer le statut
        if ($task->assigned_to === $user->id) {
            return true;
        }

        // Les participants au projet peuvent changer le statut (pour utilisateurs de l'entreprise)
        if ($task->project->participants->contains($user->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can assign the task to someone.
     */
    public function assign(User $user, Task $task): bool
    {
        // Super admin peut toujours assigner
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Cloisonnement par entreprise
        if ($user->company_id !== $task->company_id) {
            return false;
        }

        // L'admin de l'entreprise peut assigner toutes les tâches
        if ($user->isCompanyAdmin()) {
            return true;
        }

        // L'auteur de la tâche peut assigner
        if ($task->author_id === $user->id) {
            return true;
        }

        return false;
    }
}