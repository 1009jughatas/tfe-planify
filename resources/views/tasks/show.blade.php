<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-tasks text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                    <p class="text-sm text-gray-600 mt-1">Projet : {{ $task->project->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('tasks.edit', $task->id) }}" class="btn-secondary-modern">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('projects.tasks', $task->project->id) }}" class="btn-secondary-modern">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations de la tâche -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                                Détails de la Tâche
                            </h3>
                            <div class="flex items-center space-x-2">
                                <span class="badge-{{ 
                                    $task->status == 'done' ? 'success' :
                                    ($task->status == 'in-progress' ? 'warning' :
                                    ($task->status == 'blocked' ? 'danger' : 'secondary'))
                                }}">
                                    @switch($task->status)
                                        @case('todo')
                                            📋 À faire
                                            @break
                                        @case('in-progress')
                                            🔄 En cours
                                            @break
                                        @case('done')
                                            ✅ Terminée
                                            @break
                                        @case('blocked')
                                            🚫 Bloquée
                                            @break
                                        @default
                                            ❓ Inconnu
                                    @endswitch
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-4">
                            <div>
                                <p class="text-gray-700 leading-relaxed">{{ $task->description ?: 'Aucune description fournie.' }}</p>
                            </div>
                            
                            <!-- Métadonnées -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                                @if($task->due_date)
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-calendar text-gray-500"></i>
                                        <span class="text-sm text-gray-600">Échéance :</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $task->due_date->format('d/m/Y') }}</span>
                                    </div>
                                @endif
                                
                                @if($task->priority !== null)
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-flag text-gray-500"></i>
                                        <span class="text-sm text-gray-600">Priorité :</span>
                                        <span class="text-sm font-medium">
                                            @switch($task->priority)
                                                @case(0)
                                                    <span class="text-green-600">🟢 Basse</span>
                                                    @break
                                                @case(1)
                                                    <span class="text-yellow-600">🟡 Moyenne</span>
                                                    @break
                                                @case(2)
                                                    <span class="text-orange-600">🟠 Haute</span>
                                                    @break
                                                @case(3)
                                                    <span class="text-red-600">🔴 Urgente</span>
                                                    @break
                                            @endswitch
                                        </span>
                                    </div>
                                @endif
                                
                                @if($task->assigned_to)
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-user text-gray-500"></i>
                                        <span class="text-sm text-gray-600">Assignée à :</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $task->assignedUser->name ?? 'Utilisateur inconnu' }}</span>
                                    </div>
                                @endif
                                
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-clock text-gray-500"></i>
                                    <span class="text-sm text-gray-600">Créée :</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $task->created_at->format('d/m/Y à H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sous-tâches -->
                @if($task->subtasks && $task->subtasks->count() > 0)
                    <div class="modern-card">
                        <div class="modern-card-header">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-list text-primary-600 mr-2"></i>
                                Sous-tâches ({{ $task->subtasks->count() }})
                            </h3>
                        </div>
                        <div class="modern-card-body p-0">
                            <div class="divide-y divide-gray-200">
                                @foreach($task->subtasks as $subtask)
                                    <div class="p-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-500 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-tasks text-white text-xs"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $subtask->title }}</h4>
                                                    <p class="text-sm text-gray-500">{{ Str::limit($subtask->description, 60) ?: 'Aucune description' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="badge-{{ 
                                                    $subtask->status == 'done' ? 'success' :
                                                    ($subtask->status == 'in-progress' ? 'warning' :
                                                    ($subtask->status == 'blocked' ? 'danger' : 'secondary'))
                                                }}">
                                                    {{ ucfirst($subtask->status) }}
                                                </span>
                                                <a href="{{ route('tasks.show', $subtask->id) }}" class="btn-secondary-modern text-xs">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Voir
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Commentaires -->
                @if($task->comments && $task->comments->count() > 0)
                    <div class="modern-card">
                        <div class="modern-card-header">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-comments text-primary-600 mr-2"></i>
                                Commentaires ({{ $task->comments->count() }})
                            </h3>
                        </div>
                        <div class="modern-card-body">
                            <div class="space-y-4">
                                @foreach($task->comments as $comment)
                                    <div class="flex space-x-3">
                                        <div class="w-10 h-10 bg-gradient-primary rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-medium text-sm">{{ substr($comment->user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h4 class="font-medium text-gray-900">{{ $comment->user->name }}</h4>
                                                    <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-gray-700">{{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Formulaire de commentaire -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-plus text-primary-600 mr-2"></i>
                            Ajouter un Commentaire
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <form action="{{ route('comments.store', $task->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <textarea name="content" 
                                          class="input-modern" 
                                          rows="3" 
                                          placeholder="Ajoutez un commentaire..." 
                                          required></textarea>
                            </div>
                            <button type="submit" class="btn-primary-modern">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Publier
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Actions rapides -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-bolt text-primary-600 mr-2"></i>
                            Actions Rapides
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-3">
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn-secondary-modern w-full">
                                <i class="fas fa-edit mr-2"></i>
                                Modifier la Tâche
                            </a>
                            
                            <a href="{{ route('tasks.create', ['project' => $task->project->id, 'parent_id' => $task->id]) }}" 
                               class="btn-accent-modern w-full">
                                <i class="fas fa-plus mr-2"></i>
                                Ajouter une Sous-tâche
                            </a>
                            
                            <form action="{{ route('tasks.destroy', $task->id) }}" 
                                  method="POST" 
                                  class="inline w-full"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-secondary-modern w-full text-red-600 hover:bg-red-50 hover:text-red-700">
                                    <i class="fas fa-trash mr-2"></i>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Changement de statut -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-exchange-alt text-primary-600 mr-2"></i>
                            Changer le Statut
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-3">
                            <select id="status" class="input-modern" data-task-id="{{ $task->id }}" data-original-status="{{ $task->status }}">
                                <option value="todo" @if($task->status == 'todo') selected @endif>📋 À faire</option>
                                <option value="in-progress" @if($task->status == 'in-progress') selected @endif>🔄 En cours</option>
                                <option value="done" @if($task->status == 'done') selected @endif>✅ Terminée</option>
                                <option value="blocked" @if($task->status == 'blocked') selected @endif>🚫 Bloquée</option>
                            </select>
                            
                            <div class="flex space-x-2">
                                <button id="updateStatusBtn" class="btn-primary-modern flex-1" style="display: none;">
                                    <i class="fas fa-check mr-2"></i>
                                    Valider
                                </button>
                                <button id="cancelStatusBtn" class="btn-secondary-modern flex-1" style="display: none;">
                                    <i class="fas fa-times mr-2"></i>
                                    Annuler
                                </button>
                            </div>
                            
                            <p class="text-xs text-gray-500">Sélectionnez un nouveau statut et cliquez sur "Valider".</p>
                        </div>
                    </div>
                </div>

                <!-- Informations du projet -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-folder text-primary-600 mr-2"></i>
                            Projet
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-project-diagram text-gray-500"></i>
                                <span class="text-sm font-medium text-gray-900">{{ $task->project->name }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user text-gray-500"></i>
                                <span class="text-sm text-gray-600">Créé par {{ $task->project->author->name ?? 'N/A' }}</span>
                            </div>
                            <a href="{{ route('projects.show', $task->project->id) }}" class="btn-secondary-modern w-full text-sm">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Voir le Projet
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let originalStatus = $('#status').data('original-status');
            
            // Gérer le changement de sélection
            $('#status').change(function () {
                let currentStatus = $(this).val();
                
                if (currentStatus !== originalStatus) {
                    // Afficher les boutons de validation
                    $('#updateStatusBtn, #cancelStatusBtn').show();
                } else {
                    // Masquer les boutons si on revient au statut original
                    $('#updateStatusBtn, #cancelStatusBtn').hide();
                }
            });
            
            // Gérer la validation
            $('#updateStatusBtn').click(function () {
                let taskId = $('#status').data('task-id');
                let newStatus = $('#status').val();
                let selectElement = $('#status');

                // Désactiver les contrôles pendant la requête
                selectElement.prop('disabled', true);
                $('#updateStatusBtn, #cancelStatusBtn').prop('disabled', true);

                $.ajax({
                    url: `/tasks/${taskId}/update-status`,
                    method: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: newStatus
                    },
                    success: function (response) {
                        // Afficher un message de succès
                        showNotification('Statut mis à jour avec succès !', 'success');
                        
                        // Mettre à jour le statut original
                        originalStatus = newStatus;
                        
                        // Masquer les boutons
                        $('#updateStatusBtn, #cancelStatusBtn').hide();
                        
                        // Réactiver les contrôles
                        selectElement.prop('disabled', false);
                        
                        // Recharger la page après un court délai
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function (error) {
                        // Réactiver les contrôles
                        selectElement.prop('disabled', false);
                        $('#updateStatusBtn, #cancelStatusBtn').prop('disabled', false);
                        showNotification('Erreur lors de la mise à jour du statut.', 'error');
                    }
                });
            });
            
            // Gérer l'annulation
            $('#cancelStatusBtn').click(function () {
                // Remettre le statut original
                $('#status').val(originalStatus);
                
                // Masquer les boutons
                $('#updateStatusBtn, #cancelStatusBtn').hide();
            });

            function showNotification(message, type) {
                // Créer une notification toast simple
                const notification = $(`
                    <div class="fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}"></i>
                            <span>${message}</span>
                        </div>
                    </div>
                `);
                
                $('body').append(notification);
                
                // Supprimer la notification après 3 secondes
                setTimeout(function() {
                    notification.fadeOut(function() {
                        $(this).remove();
                    });
                }, 3000);
            }
        });
    </script>
</x-app-layout>