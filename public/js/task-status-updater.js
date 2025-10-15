/**
 * Système global de mise à jour des statuts de tâches
 * Met à jour tous les affichages de statut en temps réel
 */

class TaskStatusUpdater {
    constructor() {
        this.init();
    }

    init() {
        // Écouter les changements de statut de tâches
        $(document).on('taskStatusUpdated', (event, data) => {
            console.log('🔄 Mise à jour globale du statut:', data);
            this.updateAllTaskStatuses(data);
        });

        // Écouter les changements de statut de projets
        $(document).on('projectStatusUpdated', (event, data) => {
            console.log('🔄 Mise à jour globale du statut de projet:', data);
            this.updateAllProjectStatuses(data);
        });
    }

    /**
     * Met à jour tous les affichages de statut d'une tâche
     */
    updateAllTaskStatuses(data) {
        const { taskId, newStatus, projectId } = data;
        
        // 1. Mettre à jour le badge principal de la tâche
        this.updateTaskBadge(taskId, newStatus);
        
        // 2. Mettre à jour les sous-tâches si c'est une tâche parente
        this.updateSubtasks(taskId, newStatus);
        
        // 3. Mettre à jour les compteurs de sous-tâches
        this.updateSubtaskCounters(taskId, newStatus);
        
        // 4. Mettre à jour les statistiques du projet
        this.updateProjectStatistics(projectId);
        
        // 5. Mettre à jour les listes de tâches (Kanban, etc.)
        this.updateTaskLists(taskId, newStatus);
    }

    /**
     * Met à jour le badge de statut d'une tâche
     */
    updateTaskBadge(taskId, newStatus) {
        const statusInfo = this.getStatusInfo(newStatus);
        
        // Mettre à jour le badge principal
        $(`.task-status-badge[data-task-id="${taskId}"]`).each(function() {
            const badge = $(this);
            badge.removeClass('badge-success badge-primary badge-danger badge-secondary')
                  .addClass('badge-' + statusInfo.class)
                  .html(statusInfo.text);
        });

        // Mettre à jour les badges dans les cartes de tâches
        $(`.task-status[data-task-id="${taskId}"]`).each(function() {
            const statusElement = $(this);
            statusElement.removeClass('task-status-todo task-status-in-progress task-status-done task-status-blocked')
                         .addClass('task-status-' + statusInfo.cssClass)
                         .html(`<i class="${statusInfo.icon} mr-1"></i>${statusInfo.text}`);
        });
    }

    /**
     * Met à jour les sous-tâches
     */
    updateSubtasks(taskId, newStatus) {
        // Mettre à jour les statuts des sous-tâches dans les listes
        $(`.subtask-status[data-parent-id="${taskId}"]`).each(function() {
            const subtaskElement = $(this);
            const subtaskStatus = subtaskElement.data('status');
            
            // Logique pour mettre à jour les sous-tâches si nécessaire
            console.log('🔄 Mise à jour des sous-tâches pour la tâche:', taskId);
        });
    }

    /**
     * Met à jour les compteurs de sous-tâches
     */
    updateSubtaskCounters(taskId, newStatus) {
        $('.subtask-counter').each(function() {
            const counter = $(this);
            const counterTaskId = counter.data('task-id');
            
            if (counterTaskId == taskId) {
                const total = counter.data('total');
                let completed = counter.data('completed');
                
                // Mettre à jour le compteur selon le nouveau statut
                if (newStatus === 'completed' || newStatus === 'done') {
                    completed = Math.min(completed + 1, total);
                } else if (newStatus === 'todo' || newStatus === 'in-progress' || newStatus === 'blocked') {
                    completed = Math.max(completed - 1, 0);
                }
                
                // Mettre à jour l'affichage
                counter.text(`${completed}/${total} terminées`);
                counter.data('completed', completed);
                
                console.log('✅ Compteur de sous-tâches mis à jour:', `${completed}/${total} terminées`);
            }
        });
    }

    /**
     * Met à jour les statistiques du projet
     */
    updateProjectStatistics(projectId) {
        if (typeof updateProjectStatistics === 'function') {
            updateProjectStatistics();
        } else {
            console.log('⚠️ Fonction updateProjectStatistics non disponible');
        }
    }

    /**
     * Met à jour les listes de tâches (Kanban, etc.)
     */
    updateTaskLists(taskId, newStatus) {
        // Mettre à jour les compteurs dans les colonnes Kanban
        this.updateKanbanCounters(newStatus);
        
        // Déplacer la tâche entre les colonnes si nécessaire
        this.moveTaskBetweenColumns(taskId, newStatus);
    }

    /**
     * Met à jour les compteurs des colonnes Kanban
     */
    updateKanbanCounters(newStatus) {
        // Cette fonction sera implémentée selon les besoins spécifiques
        console.log('🔄 Mise à jour des compteurs Kanban pour le statut:', newStatus);
    }

    /**
     * Déplace une tâche entre les colonnes Kanban
     */
    moveTaskBetweenColumns(taskId, newStatus) {
        // Cette fonction sera implémentée selon les besoins spécifiques
        console.log('🔄 Déplacement de la tâche entre colonnes:', taskId, newStatus);
    }

    /**
     * Met à jour tous les affichages de statut d'un projet
     */
    updateAllProjectStatuses(data) {
        const { projectId, newStatus } = data;
        
        // Mettre à jour les badges de statut du projet
        $(`.project-status-badge[data-project-id="${projectId}"]`).each(function() {
            const badge = $(this);
            const statusInfo = this.getProjectStatusInfo(newStatus);
            badge.removeClass('badge-success badge-primary badge-danger badge-warning badge-secondary')
                  .addClass('badge-' + statusInfo.class)
                  .html(statusInfo.text);
        });
    }

    /**
     * Obtient les informations de statut d'une tâche
     */
    getStatusInfo(status) {
        const statusMap = {
            'todo': { text: '📋 À faire', class: 'secondary', cssClass: 'todo', icon: 'fas fa-circle' },
            'pending': { text: '⏳ En attente', class: 'secondary', cssClass: 'todo', icon: 'fas fa-clock' },
            'in-progress': { text: '🔄 En cours', class: 'primary', cssClass: 'in-progress', icon: 'fas fa-play-circle' },
            'done': { text: '✅ Terminée', class: 'success', cssClass: 'done', icon: 'fas fa-check-circle' },
            'completed': { text: '✅ Terminée', class: 'success', cssClass: 'done', icon: 'fas fa-check-circle' },
            'blocked': { text: '🚫 Bloquée', class: 'danger', cssClass: 'blocked', icon: 'fas fa-exclamation-circle' }
        };
        
        return statusMap[status] || statusMap['todo'];
    }

    /**
     * Obtient les informations de statut d'un projet
     */
    getProjectStatusInfo(status) {
        const statusMap = {
            'pending': { text: '⏳ En attente', class: 'secondary' },
            'planning': { text: '📋 En planification', class: 'secondary' },
            'active': { text: '🚀 Actif', class: 'primary' },
            'on-hold': { text: '⏸️ En pause', class: 'warning' },
            'completed': { text: '✅ Terminé', class: 'success' },
            'cancelled': { text: '❌ Annulé', class: 'danger' }
        };
        
        return statusMap[status] || statusMap['pending'];
    }
}

// Initialiser le système de mise à jour des statuts
$(document).ready(function() {
    window.taskStatusUpdater = new TaskStatusUpdater();
    console.log('✅ Système de mise à jour des statuts initialisé');
});
