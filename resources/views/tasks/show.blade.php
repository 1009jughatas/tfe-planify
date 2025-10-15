<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                    <img src="{{ asset('images/logo.png') }}" alt="Planify" class="w-8 h-8 object-contain filter brightness-0 invert">
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Projet : 
                        <a href="{{ route('projects.show', $task->project->id) }}" class="text-blue-600 hover:text-blue-700 font-medium underline">
                            {{ $task->project->name }}
                        </a>
                    </p>
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- En-tête de la tâche avec informations clés -->
        <div class="modern-card mb-6">
            <div class="modern-card-body">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Informations principales -->
                    <div class="lg:col-span-2">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <span class="badge-{{ 
                                        $task->status == 'done' ? 'success' :
                                        ($task->status == 'in-progress' ? 'primary' :
                                        ($task->status == 'blocked' ? 'danger' : 'secondary'))
                                    }}">
                                        @switch($task->status)
                                            @case('todo') 📋 À faire @break
                                            @case('in-progress') 🔄 En cours @break
                                            @case('done') ✅ Terminée @break
                                            @case('blocked') 🚫 Bloquée @break
                                            @default ❓ Inconnu
                                        @endswitch
                                    </span>
                                    @if($task->priority !== null)
                                        <span class="badge-{{ 
                                            $task->priority == 3 ? 'danger' :
                                            ($task->priority == 2 ? 'warning' :
                                            ($task->priority == 1 ? 'secondary' : 'success'))
                                        }}">
                                            @switch($task->priority)
                                                @case(0) 🟢 Basse @break
                                                @case(1) 🟡 Moyenne @break
                                                @case(2) 🟠 Haute @break
                                                @case(3) 🔴 Urgente @break
                                            @endswitch
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-gray-700 leading-relaxed mb-4">
                                    {{ $task->description ?: 'Aucune description fournie.' }}
                                </p>
                                
                                <div class="flex items-center space-x-6 text-sm text-gray-600">
                                    @if($task->due_date)
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar mr-2"></i>
                                            <span class="{{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-red-600 font-medium' : '' }}">
                                                {{ $task->due_date->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    @endif
                                    @if($task->assigned_to)
                                        <div class="flex items-center">
                                            <i class="fas fa-user mr-2"></i>
                                            <span>{{ $task->assignedUser->name ?? 'Utilisateur inconnu' }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span>Créée {{ $task->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="space-y-3">
                        <a href="{{ $task->project->company_id ? route('entreprise.tasks.edit', $task->id) : route('tasks.edit', $task->id) }}" class="btn-primary-modern w-full">
                            <i class="fas fa-edit mr-2"></i>
                            Modifier la Tâche
                        </a>
                        <a href="{{ $task->project->company_id ? route('entreprise.tasks.create', ['project' => $task->project->id, 'parent_id' => $task->id]) : route('tasks.create', ['project' => $task->project->id, 'parent_id' => $task->id]) }}" class="btn-secondary-modern w-full">
                            <i class="fas fa-plus mr-2"></i>
                            Ajouter une Sous-tâche
                        </a>
                        <a href="{{ $task->project->company_id ? route('entreprise.projets.show', $task->project->id) : route('projects.show', $task->project->id) }}" class="btn-secondary-modern w-full">
                            <i class="fas fa-folder-open mr-2"></i>
                            Voir le Projet
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Sous-tâches -->
                @if($task->subtasks && $task->subtasks->count() > 0)
                    <div class="modern-card">
                        <div class="modern-card-header">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-list text-blue-600 mr-2"></i>
                                Sous-tâches
                                <span class="ml-2 badge-secondary">{{ $task->subtasks->count() }}</span>
                            </h3>
                        </div>
                        <div class="modern-card-body">
                            <div class="space-y-3">
                                @foreach($task->subtasks as $subtask)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-500 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-tasks text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $subtask->title }}</h4>
                                                <p class="text-sm text-gray-500">{{ Str::limit($subtask->description, 60) ?: 'Aucune description' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="badge-{{ 
                                                $subtask->status == 'done' ? 'success' :
                                                ($subtask->status == 'in-progress' ? 'primary' :
                                                ($subtask->status == 'blocked' ? 'danger' : 'secondary'))
                                            }}">
                                                {{ ucfirst($subtask->status) }}
                                            </span>
                                            <a href="{{ route('tasks.show', $subtask->id) }}" class="btn-secondary-modern text-sm">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir
                                            </a>
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
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-comments text-blue-600 mr-2"></i>
                                Commentaires
                                <span class="ml-2 badge-secondary">{{ $task->comments->count() }}</span>
                            </h3>
                        </div>
                        <div class="modern-card-body">
                            <div class="space-y-4">
                                @foreach($task->comments as $comment)
                                    <div class="flex space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-medium text-sm">{{ substr($comment->user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <div class="flex items-center justify-between mb-2">
                                                    <div class="flex items-center space-x-2">
                                                        <h4 class="font-medium text-gray-900">{{ $comment->user->name }}</h4>
                                                        @if($comment->user_id === auth()->id())
                                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Vous</span>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                                        @if($comment->user_id === auth()->id() || auth()->user()->is_admin())
                                                            <button 
                                                                onclick="confirmDeleteComment({{ $comment->id }})"
                                                                class="text-red-500 hover:text-red-700 text-xs p-1 rounded-full hover:bg-red-50 transition-colors duration-200"
                                                                title="Supprimer le commentaire">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </div>
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
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-plus text-blue-600 mr-2"></i>
                            Ajouter un Commentaire
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <form action="{{ $task->project->company_id ? route('entreprise.comments.store', $task->id) : route('comments.store', $task->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="content" class="form-label-modern">Votre commentaire</label>
                                <textarea name="content" 
                                          id="content"
                                          class="input-modern" 
                                          rows="4" 
                                          placeholder="Ajoutez un commentaire à cette tâche..." 
                                          required></textarea>
                            </div>
                            <button type="submit" class="btn-primary-modern">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Publier le Commentaire
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Changement de statut -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-exchange-alt text-blue-600 mr-2"></i>
                            Gestion du Statut
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Modifiez le statut de cette tâche</p>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-4">
                            <div>
                                <label for="status" class="form-label-modern">Statut de la tâche</label>
                                <select id="status" class="input-modern" data-task-id="{{ $task->id }}" data-original-status="{{ $task->status }}">
                                    <option value="todo" @if($task->status == 'todo') selected @endif>📋 À faire</option>
                                    <option value="in-progress" @if($task->status == 'in-progress') selected @endif>🔄 En cours</option>
                                    <option value="done" @if($task->status == 'done') selected @endif>✅ Terminée</option>
                                    <option value="blocked" @if($task->status == 'blocked') selected @endif>🚫 Bloquée</option>
                                </select>
                            </div>
                            
                            <button id="updateStatusBtn" class="btn-primary-modern w-full">
                                <i class="fas fa-check mr-2"></i>
                                Mettre à Jour le Statut
                            </button>
                            
                            <p class="text-xs text-gray-500 text-center" id="statusHelp">
                                Sélectionnez un nouveau statut et cliquez sur "Mettre à Jour"
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Informations du projet -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-folder text-blue-600 mr-2"></i>
                            Informations du Projet
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-project-diagram text-gray-500"></i>
                                <span class="text-sm font-medium text-gray-900">{{ $task->project->name }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user text-gray-500"></i>
                                <span class="text-sm text-gray-600">Créé par {{ $task->project->author->name ?? 'N/A' }}</span>
                            </div>
                            @if($task->project->status)
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-flag text-gray-500"></i>
                                    <span class="text-sm text-gray-600">Statut :</span>
                                    <span class="badge-{{ 
                                        $task->project->status == 'completed' ? 'success' :
                                        ($task->project->status == 'active' ? 'primary' :
                                        ($task->project->status == 'on-hold' ? 'warning' :
                                        ($task->project->status == 'cancelled' ? 'danger' : 'secondary')))
                                    }}">
                                        {{ ucfirst($task->project->status) }}
                                    </span>
                                </div>
                            @endif
                            <a href="{{ route('projects.show', $task->project->id) }}" class="btn-secondary-modern w-full text-sm">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Voir le Projet Complet
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Actions avancées -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-cog text-blue-600 mr-2"></i>
                            Actions Avancées
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-3">
                            <form action="{{ route('tasks.destroy', $task->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-secondary-modern w-full text-red-600 hover:bg-red-50 hover:text-red-700 border-red-200">
                                    <i class="fas fa-trash mr-2"></i>
                                    Supprimer la Tâche
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Styles spécifiques pour la page de détail de tâche */
        .modern-card {
            @apply bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-md hover:-translate-y-1;
        }
        
        .modern-card-header {
            @apply px-6 py-4 border-b border-gray-200 bg-gray-50;
        }
        
        .modern-card-body {
            @apply px-6 py-4;
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .modern-card-header {
                @apply px-4 py-3;
            }
            
            .modern-card-body {
                @apply px-4 py-3;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let originalStatus = $('#status').data('original-status');
            
            // Gérer la validation du statut
            $('#updateStatusBtn').click(function () {
                let taskId = $('#status').data('task-id');
                let newStatus = $('#status').val();
                let selectElement = $('#status');
                
                // Vérifier si le statut a vraiment changé
                if (newStatus === originalStatus) {
                    showNotification('Aucun changement détecté. Le statut est déjà : ' + newStatus, 'info');
                    return;
                }

                // Désactiver les contrôles pendant la requête
                selectElement.prop('disabled', true);
                $('#updateStatusBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Mise à jour...');

                $.ajax({
                    url: `{{ $task->project->company_id ? '/entreprise/tasks/' : '/tasks/' }}${taskId}/update-status`,
                    method: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: newStatus
                    },
                    success: function (response) {
                        showNotification('Statut mis à jour avec succès !', 'success');
                        originalStatus = newStatus;
                        
                        // Recharger la page après un court délai
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    },
                    error: function (xhr, status, error) {
                        // Réactiver les contrôles
                        selectElement.prop('disabled', false);
                        $('#updateStatusBtn').prop('disabled', false).html('<i class="fas fa-check mr-2"></i>Mettre à Jour le Statut');
                        
                        let errorMessage = 'Erreur lors de la mise à jour du statut.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        showNotification(errorMessage, 'error');
                    }
                });
            });

            function showNotification(message, type) {
                let bgColor, textColor, icon;
                
                switch(type) {
                    case 'success':
                        bgColor = 'bg-green-500';
                        textColor = 'text-white';
                        icon = 'check';
                        break;
                    case 'error':
                        bgColor = 'bg-red-500';
                        textColor = 'text-white';
                        icon = 'exclamation';
                        break;
                    case 'info':
                        bgColor = 'bg-blue-500';
                        textColor = 'text-white';
                        icon = 'info-circle';
                        break;
                    default:
                        bgColor = 'bg-gray-500';
                        textColor = 'text-white';
                        icon = 'info';
                }
                
                const notification = $(`
                    <div class="fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${bgColor} ${textColor}">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-${icon}"></i>
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
        
        // Fonction de confirmation de suppression de commentaire
        function confirmDeleteComment(commentId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ? Cette action est irréversible.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/comments/${commentId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>