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
        // Tous les utilisateurs authentifiés peuvent voir leurs tâches
        return true;
    }

    /**
     * Determine if the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        // Les admins peuvent voir toutes les tâches
        if ($user->is_admin()) {
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
        // Tous les utilisateurs authentifiés peuvent créer des tâches
        // (la limitation est au niveau du projet)
        return true;
    }

    /**
     * Determine if the user can update the task.
     */
    public function update(User $user, Task $task): bool
    {
        // Les admins peuvent modifier toutes les tâches
        if ($user->is_admin()) {
            return true;
        }

        // L'auteur peut modifier ses tâches
        if ($task->author_id === $user->id) {
            return true;
        }

        // L'utilisateur assigné peut modifier la tâche (pour utilisateurs premium)
        if ($user->is_premium && $task->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the task.
     */
    public function delete(User $user, Task $task): bool
    {
        // Les admins peuvent supprimer toutes les tâches
        if ($user->is_admin()) {
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
        // Les admins peuvent toujours changer le statut
        if ($user->is_admin()) {
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

        // Les participants au projet peuvent changer le statut (pour utilisateurs premium)
        if ($user->is_premium && $task->project->participants->contains($user->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can assign the task to someone.
     */
    public function assign(User $user, Task $task): bool
    {
        // Les admins peuvent toujours assigner
        if ($user->is_admin()) {
            return true;
        }

        // Seuls les utilisateurs premium peuvent assigner des tâches
        if (!$user->is_premium) {
            return false;
        }

        // L'auteur du projet peut assigner
        if ($task->project->author_id === $user->id) {
            return true;
        }

        // L'auteur de la tâche peut assigner
        return $task->author_id === $user->id;
    }
}