<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mes Projets</h1>
                <p class="text-sm text-gray-600 mt-1">Tableau Kanban - Gérez vos projets par statut</p>
            </div>
            @php
                $canCreate = Auth::user()->is_admin() || 
                             Auth::user()->is_premium || 
                             Auth::user()->projects()->count() < 3;
            @endphp
            @if ($canCreate)
                <a href="{{ route('projects.create') }}" class="btn-primary-modern">
                    <i class="fas fa-plus mr-2"></i>
                    Nouveau projet
                </a>
            @else
                <button class="btn-secondary-modern" disabled title="Limite atteinte">
                    <i class="fas fa-lock mr-2"></i>
                    Limite atteinte
                </button>
            @endif
        </div>
    </x-slot>

    <div class="trello-kanban-container">
        <div class="trello-kanban-board" id="kanban-board">
            <!-- Alertes de limitation -->
            @if (!Auth::user()->is_premium && !Auth::user()->is_admin())
                @php
                    $projectCount = Auth::user()->projects()->count();
                    $projectLimit = 3;
                @endphp
                @if ($projectCount >= $projectLimit)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                            <div>
                                <h3 class="text-sm font-medium text-yellow-800">Limite de projets atteinte</h3>
                                <p class="text-sm text-yellow-700 mt-1">
                                    Vous avez atteint la limite de {{ $projectLimit }} projets pour les utilisateurs gratuits.
                                    <a href="{{ route('premium.show') }}" class="font-medium underline hover:text-yellow-600">
                                        Passez en premium
                                    </a>
                                    pour créer plus de projets.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if ($projects->isEmpty())
                <!-- État vide -->
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-project-diagram text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun projet</h3>
                    <p class="text-gray-600 mb-6">Commencez par créer votre premier projet pour organiser vos tâches.</p>
                    @if ($canCreate)
                        <a href="{{ route('projects.create') }}" class="btn-primary-modern">
                            <i class="fas fa-plus mr-2"></i>
                            Créer mon premier projet
                        </a>
                    @endif
                </div>
            @else
                <!-- Colonnes Kanban selon les spécifications -->
                <div class="trello-columns">
                    <!-- À faire (To Do) -->
                    <div class="trello-column" data-status="todo">
                        <div class="trello-column-header">
                            <h3 class="trello-column-title">
                                <i class="fas fa-circle text-gray-400 mr-2"></i>
                                À faire
                            </h3>
                            <span class="trello-column-count">
                                @php $todoProjects = $projects->where('status', 'todo') @endphp
                                {{ $todoProjects->count() }}
                            </span>
                        </div>
                        <div class="trello-column-content" data-status="todo">
                            @foreach ($todoProjects as $project)
                                <div class="trello-card" 
                                     draggable="true" 
                                     data-project-id="{{ $project->id }}"
                                     data-status="todo"
                                     onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                    <div class="trello-card-header">
                                        <h4 class="trello-card-title">{{ $project->name }}</h4>
                                        @if($project->end_date)
                                            <div class="trello-card-deadline">
                                                <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                                                <span class="deadline-text">{{ $project->end_date->format('d/m/Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($project->description)
                                        <div class="trello-card-description">
                                            <p>{{ Str::limit($project->description, 100, '...') }}</p>
                                        </div>
                                    @endif
                                    
                                    <!-- Barre de progression -->
                                    <div class="trello-card-progress">
                                        <div class="progress-label">
                                            <span>Progression</span>
                                            <span class="progress-percentage">
                                                @php
                                                    $totalTasks = $project->tasks->count();
                                                    $completedTasks = $project->tasks->where('status', 'done')->count();
                                                    $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                                @endphp
                                                {{ $progress }}%
                                            </span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Footer avec métadonnées et avatars -->
                                    <div class="trello-card-footer">
                                        <div class="trello-card-meta">
                                            <span class="meta-item">
                                                <i class="fas fa-tasks text-gray-400 mr-1"></i>
                                                {{ $project->tasks->count() }}
                                            </span>
                                            <span class="meta-item">
                                                <i class="fas fa-users text-gray-400 mr-1"></i>
                                                {{ $project->participants->count() }}
                                            </span>
                                        </div>
                                        
                                        <div class="trello-card-avatars">
                                            @if($project->author)
                                                <div class="trello-avatar" title="{{ $project->author->name }}">
                                                    {{ substr($project->author->name, 0, 1) }}
                                                </div>
                                            @endif
                                            @if($project->participants->count() > 0)
                                                @foreach($project->participants->take(2) as $participant)
                                                    <div class="trello-avatar" title="{{ $participant->name }}">
                                                        {{ substr($participant->name, 0, 1) }}
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Bouton Ajouter un projet -->
                        @if ($canCreate)
                            <div class="trello-add-card">
                                <button class="trello-add-btn" onclick="window.location.href='{{ route('projects.create') }}'">
                                    <i class="fas fa-plus mr-2"></i>
                                    Ajouter un projet
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- En cours (In Progress) -->
                    <div class="trello-column" data-status="in_progress">
                        <div class="trello-column-header">
                            <h3 class="trello-column-title">
                                <i class="fas fa-play text-blue-500 mr-2"></i>
                                En cours
                            </h3>
                            <span class="trello-column-count">
                                @php $inProgressProjects = $projects->where('status', 'in_progress') @endphp
                                {{ $inProgressProjects->count() }}
                            </span>
                        </div>
                        <div class="trello-column-content" data-status="in_progress">
                            @foreach ($inProgressProjects as $project)
                                <div class="trello-card" 
                                     draggable="true" 
                                     data-project-id="{{ $project->id }}"
                                     data-status="in_progress"
                                     onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                    <div class="trello-card-header">
                                        <h4 class="trello-card-title">{{ $project->name }}</h4>
                                        @if($project->end_date)
                                            <div class="trello-card-deadline">
                                                <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                                                <span class="deadline-text">{{ $project->end_date->format('d/m/Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($project->description)
                                        <div class="trello-card-description">
                                            <p>{{ Str::limit($project->description, 100, '...') }}</p>
                                        </div>
                                    @endif
                                    
                                    <div class="trello-card-progress">
                                        <div class="progress-label">
                                            <span>Progression</span>
                                            <span class="progress-percentage">
                                                @php
                                                    $totalTasks = $project->tasks->count();
                                                    $completedTasks = $project->tasks->where('status', 'done')->count();
                                                    $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                                @endphp
                                                {{ $progress }}%
                                            </span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="trello-card-footer">
                                        <div class="trello-card-meta">
                                            <span class="meta-item">
                                                <i class="fas fa-tasks text-gray-400 mr-1"></i>
                                                {{ $project->tasks->count() }}
                                            </span>
                                            <span class="meta-item">
                                                <i class="fas fa-users text-gray-400 mr-1"></i>
                                                {{ $project->participants->count() }}
                                            </span>
                                        </div>
                                        
                                        <div class="trello-card-avatars">
                                            @if($project->author)
                                                <div class="trello-avatar" title="{{ $project->author->name }}">
                                                    {{ substr($project->author->name, 0, 1) }}
                                                </div>
                                            @endif
                                            @if($project->participants->count() > 0)
                                                @foreach($project->participants->take(2) as $participant)
                                                    <div class="trello-avatar" title="{{ $participant->name }}">
                                                        {{ substr($participant->name, 0, 1) }}
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if ($canCreate)
                            <div class="trello-add-card">
                                <button class="trello-add-btn" onclick="window.location.href='{{ route('projects.create') }}'">
                                    <i class="fas fa-plus mr-2"></i>
                                    Ajouter un projet
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Bloqué -->
                    <div class="trello-column" data-status="blocked">
                        <div class="trello-column-header">
                            <h3 class="trello-column-title">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                Bloqué
                            </h3>
                            <span class="trello-column-count">
                                @php $blockedProjects = $projects->where('status', 'blocked') @endphp
                                {{ $blockedProjects->count() }}
                            </span>
                        </div>
                        <div class="trello-column-content" data-status="blocked">
                            @foreach ($blockedProjects as $project)
                                <div class="trello-card" 
                                     draggable="true" 
                                     data-project-id="{{ $project->id }}"
                                     data-status="blocked"
                                     onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                    <div class="trello-card-header">
                                        <h4 class="trello-card-title">{{ $project->name }}</h4>
                                        @if($project->end_date)
                                            <div class="trello-card-deadline">
                                                <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                                                <span class="deadline-text">{{ $project->end_date->format('d/m/Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($project->description)
                                        <div class="trello-card-description">
                                            <p>{{ Str::limit($project->description, 100, '...') }}</p>
                                        </div>
                                    @endif
                                    
                                    <div class="trello-card-progress">
                                        <div class="progress-label">
                                            <span>Progression</span>
                                            <span class="progress-percentage">
                                                @php
                                                    $totalTasks = $project->tasks->count();
                                                    $completedTasks = $project->tasks->where('status', 'done')->count();
                                                    $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                                @endphp
                                                {{ $progress }}%
                                            </span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="trello-card-footer">
                                        <div class="trello-card-meta">
                                            <span class="meta-item">
                                                <i class="fas fa-tasks text-gray-400 mr-1"></i>
                                                {{ $project->tasks->count() }}
                                            </span>
                                            <span class="meta-item">
                                                <i class="fas fa-users text-gray-400 mr-1"></i>
                                                {{ $project->participants->count() }}
                                            </span>
                                        </div>
                                        
                                        <div class="trello-card-avatars">
                                            @if($project->author)
                                                <div class="trello-avatar" title="{{ $project->author->name }}">
                                                    {{ substr($project->author->name, 0, 1) }}
                                                </div>
                                            @endif
                                            @if($project->participants->count() > 0)
                                                @foreach($project->participants->take(2) as $participant)
                                                    <div class="trello-avatar" title="{{ $participant->name }}">
                                                        {{ substr($participant->name, 0, 1) }}
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if ($canCreate)
                            <div class="trello-add-card">
                                <button class="trello-add-btn" onclick="window.location.href='{{ route('projects.create') }}'">
                                    <i class="fas fa-plus mr-2"></i>
                                    Ajouter un projet
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Terminé (Done) -->
                    <div class="trello-column" data-status="done">
                        <div class="trello-column-header">
                            <h3 class="trello-column-title">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Terminé
                            </h3>
                            <span class="trello-column-count">
                                @php $doneProjects = $projects->where('status', 'done') @endphp
                                {{ $doneProjects->count() }}
                            </span>
                        </div>
                        <div class="trello-column-content" data-status="done">
                            @foreach ($doneProjects as $project)
                                <div class="trello-card" 
                                     draggable="true" 
                                     data-project-id="{{ $project->id }}"
                                     data-status="done"
                                     onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                    <div class="trello-card-header">
                                        <h4 class="trello-card-title">{{ $project->name }}</h4>
                                        @if($project->end_date)
                                            <div class="trello-card-deadline">
                                                <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                                                <span class="deadline-text">{{ $project->end_date->format('d/m/Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($project->description)
                                        <div class="trello-card-description">
                                            <p>{{ Str::limit($project->description, 100, '...') }}</p>
                                        </div>
                                    @endif
                                    
                                    <div class="trello-card-progress">
                                        <div class="progress-label">
                                            <span>Progression</span>
                                            <span class="progress-percentage">
                                                @php
                                                    $totalTasks = $project->tasks->count();
                                                    $completedTasks = $project->tasks->where('status', 'done')->count();
                                                    $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 100;
                                                @endphp
                                                {{ $progress }}%
                                            </span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="trello-card-footer">
                                        <div class="trello-card-meta">
                                            <span class="meta-item">
                                                <i class="fas fa-tasks text-gray-400 mr-1"></i>
                                                {{ $project->tasks->count() }}
                                            </span>
                                            <span class="meta-item">
                                                <i class="fas fa-users text-gray-400 mr-1"></i>
                                                {{ $project->participants->count() }}
                                            </span>
                                        </div>
                                        
                                        <div class="trello-card-avatars">
                                            @if($project->author)
                                                <div class="trello-avatar" title="{{ $project->author->name }}">
                                                    {{ substr($project->author->name, 0, 1) }}
                                                </div>
                                            @endif
                                            @if($project->participants->count() > 0)
                                                @foreach($project->participants->take(2) as $participant)
                                                    <div class="trello-avatar" title="{{ $participant->name }}">
                                                        {{ substr($participant->name, 0, 1) }}
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if ($canCreate)
                            <div class="trello-add-card">
                                <button class="trello-add-btn" onclick="window.location.href='{{ route('projects.create') }}'">
                                    <i class="fas fa-plus mr-2"></i>
                                    Ajouter un projet
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Design Trello avec fond violet dégradé */
        .trello-kanban-container {
            min-height: calc(100vh - 120px);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .trello-kanban-board {
            @apply w-full;
        }
        
        /* Colonnes Trello */
        .trello-columns {
            @apply flex gap-6 overflow-x-auto pb-4;
            min-height: 600px;
        }
        
        .trello-column {
            @apply bg-gray-100 rounded-lg;
            width: 300px;
            flex-shrink: 0;
            min-height: 500px;
        }
        
        .trello-column-header {
            @apply p-4 border-b border-gray-200;
        }
        
        .trello-column-title {
            @apply text-lg font-semibold text-gray-800 flex items-center;
            margin: 0;
        }
        
        .trello-column-count {
            @apply ml-auto bg-gray-200 text-gray-700 text-sm font-medium px-2 py-1 rounded-full;
        }
        
        .trello-column-content {
            @apply p-4 space-y-3 min-h-400;
        }
        
        /* Cartes Trello */
        .trello-card {
            @apply bg-white rounded-lg shadow-sm cursor-pointer transition-all duration-200;
            padding: 16px;
            border-left: 4px solid transparent;
        }
        
        .trello-card:hover {
            @apply shadow-md transform translate-y-1;
        }
        
        .trello-card[draggable="true"]:hover {
            cursor: grab;
        }
        
        .trello-card:active {
            cursor: grabbing;
        }
        
        /* Header des cartes */
        .trello-card-header {
            @apply mb-3;
        }
        
        .trello-card-title {
            @apply text-base font-semibold text-gray-900 mb-2 leading-tight;
            margin: 0;
        }
        
        .trello-card-deadline {
            @apply flex items-center text-sm text-gray-600;
        }
        
        .deadline-text {
            @apply font-medium;
        }
        
        /* Description */
        .trello-card-description {
            @apply mb-3;
        }
        
        .trello-card-description p {
            @apply text-sm text-gray-600 leading-relaxed;
        }
        
        /* Barre de progression */
        .trello-card-progress {
            @apply mb-3;
        }
        
        .progress-label {
            @apply flex items-center justify-between text-xs text-gray-600 mb-1;
        }
        
        .progress-percentage {
            @apply font-semibold text-gray-800;
        }
        
        .progress-bar {
            @apply w-full h-2 bg-gray-200 rounded-full overflow-hidden;
        }
        
        .progress-fill {
            @apply h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full transition-all duration-300;
        }
        
        /* Footer des cartes */
        .trello-card-footer {
            @apply flex items-center justify-between;
        }
        
        .trello-card-meta {
            @apply flex items-center space-x-3;
        }
        
        .meta-item {
            @apply flex items-center text-xs text-gray-500;
        }
        
        .trello-card-avatars {
            @apply flex items-center space-x-1;
        }
        
        .trello-avatar {
            @apply w-6 h-6 bg-gradient-to-br from-primary-500 to-primary-600 text-white text-xs font-semibold rounded-full flex items-center justify-center border-2 border-white shadow-sm;
        }
        
        /* Bouton Ajouter */
        .trello-add-card {
            @apply p-4;
        }
        
        .trello-add-btn {
            @apply w-full py-3 px-4 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-primary-400 hover:text-primary-600 transition-all duration-200 flex items-center justify-center space-x-2;
        }
        
        .trello-add-btn:hover {
            @apply bg-primary-50;
        }
        
        /* Drag & Drop States */
        .trello-column.drag-over {
            @apply bg-blue-50 border-2 border-dashed border-blue-300;
        }
        
        .trello-card.dragging {
            @apply opacity-50 transform rotate-3 scale-105;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .trello-kanban-container {
                @apply p-4;
            }
            
            .trello-columns {
                @apply flex-col space-y-4;
            }
            
            .trello-column {
                @apply w-full;
            }
        }
        
        /* Scrollbar personnalisée */
        .trello-columns::-webkit-scrollbar {
            height: 8px;
        }
        
        .trello-columns::-webkit-scrollbar-track {
            @apply bg-gray-200 rounded;
        }
        
        .trello-columns::-webkit-scrollbar-thumb {
            @apply bg-gray-400 rounded;
        }
        
        .trello-columns::-webkit-scrollbar-thumb:hover {
            @apply bg-gray-500;
        }
    </style>

    <!-- JavaScript pour le drag & drop -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.trello-card');
            const columns = document.querySelectorAll('.trello-column-content');
            
            let draggedCard = null;
            
            // Événements de drag pour les cartes
            cards.forEach(card => {
                card.addEventListener('dragstart', handleDragStart);
                card.addEventListener('dragend', handleDragEnd);
            });
            
            // Événements de drop pour les colonnes
            columns.forEach(column => {
                column.addEventListener('dragover', handleDragOver);
                column.addEventListener('drop', handleDrop);
                column.addEventListener('dragenter', handleDragEnter);
                column.addEventListener('dragleave', handleDragLeave);
            });
            
            function handleDragStart(e) {
                draggedCard = this;
                this.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.outerHTML);
            }
            
            function handleDragEnd(e) {
                this.classList.remove('dragging');
                draggedCard = null;
                
                // Retirer les classes de drag-over de toutes les colonnes
                columns.forEach(column => {
                    column.parentElement.classList.remove('drag-over');
                });
            }
            
            function handleDragOver(e) {
                if (e.preventDefault) {
                    e.preventDefault();
                }
                e.dataTransfer.dropEffect = 'move';
                return false;
            }
            
            function handleDragEnter(e) {
                this.parentElement.classList.add('drag-over');
            }
            
            function handleDragLeave(e) {
                this.parentElement.classList.remove('drag-over');
            }
            
            function handleDrop(e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }
                
                if (draggedCard) {
                    const newStatus = this.getAttribute('data-status');
                    const projectId = draggedCard.getAttribute('data-project-id');
                    
                    // Mettre à jour le statut via Ajax
                    updateProjectStatus(projectId, newStatus);
                    
                    // Déplacer la carte visuellement
                    this.appendChild(draggedCard);
                    draggedCard.setAttribute('data-status', newStatus);
                }
                
                this.parentElement.classList.remove('drag-over');
                return false;
            }
            
            function updateProjectStatus(projectId, newStatus) {
                fetch(`/projects/${projectId}/move`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        // Afficher une notification de succès
                        showNotification('Projet déplacé avec succès !', 'success');
                        
                        // Mettre à jour les compteurs des colonnes
                        updateColumnCounts();
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors du déplacement du projet', 'error');
                    
                    // Recharger la page en cas d'erreur
                    window.location.reload();
                });
            }
            
            function updateColumnCounts() {
                document.querySelectorAll('.trello-column').forEach(column => {
                    const status = column.getAttribute('data-status');
                    const count = column.querySelectorAll('.trello-card').length;
                    const countElement = column.querySelector('.trello-column-count');
                    if (countElement) {
                        countElement.textContent = count;
                    }
                });
            }
            
            function showNotification(message, type) {
                // Créer une notification toast
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                    type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
                }`;
                notification.textContent = message;
                
                document.body.appendChild(notification);
                
                // Supprimer la notification après 3 secondes
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        });
    </script>
</x-app-layout>