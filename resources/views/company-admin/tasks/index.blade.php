@extends('company-admin.layouts.app')

@section('title', 'Gestion des tâches')
@section('page-title', 'Gestion des tâches')

@section('content')
<!-- En-tête avec bouton d'action -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Gestion des tâches</h2>
        <p class="text-gray-600 mt-1">Surveillez toutes les tâches de votre équipe</p>
    </div>
    <div class="flex space-x-3 mt-4 sm:mt-0">
        <button onclick="toggleView()" class="btn-secondary-modern">
            <i class="fas fa-calendar-alt mr-2" id="view-icon"></i>
            <span id="view-text">Vue calendrier</span>
        </button>
        <a href="{{ route('tasks.create') }}" class="btn-primary-modern">
            <i class="fas fa-plus mr-2"></i>
            Créer une tâche
        </a>
    </div>
</div>

<!-- Filtres et recherche -->
<div class="modern-card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
            <input type="text" id="search" placeholder="Titre de la tâche..." 
                   class="input-modern" onkeyup="filterTasks()">
        </div>
        <div>
            <label for="project_filter" class="block text-sm font-medium text-gray-700 mb-1">Projet</label>
            <select id="project_filter" class="input-modern" onchange="filterTasks()">
                <option value="">Tous les projets</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <select id="status_filter" class="input-modern" onchange="filterTasks()">
                <option value="">Tous les statuts</option>
                <option value="todo">À faire</option>
                <option value="in_progress">En cours</option>
                <option value="review">En révision</option>
                <option value="done">Terminé</option>
            </select>
        </div>
        <div>
            <label for="assignee_filter" class="block text-sm font-medium text-gray-700 mb-1">Assigné à</label>
            <select id="assignee_filter" class="input-modern" onchange="filterTasks()">
                <option value="">Tous les membres</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button onclick="resetFilters()" class="btn-secondary-modern w-full">
                <i class="fas fa-refresh mr-2"></i>
                Réinitialiser
            </button>
        </div>
    </div>
</div>

<!-- Vue liste des tâches -->
<div id="list-view" class="modern-card">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tâche</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigné à</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Échéance</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tasks as $task)
                <tr class="task-row" 
                    data-title="{{ strtolower($task->title) }}" 
                    data-project="{{ $task->project_id }}" 
                    data-status="{{ $task->status }}" 
                    data-assignee="{{ $task->assigned_to }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tasks text-white text-sm"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($task->description, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-folder text-white text-xs"></i>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $task->project->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($task->assignedTo)
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-xs">{{ substr($task->assignedTo->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $task->assignedTo->name }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-sm text-gray-500">Non assignée</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @switch($task->status)
                            @case('todo')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-circle mr-1"></i>À faire
                                </span>
                                @break
                            @case('in_progress')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-play mr-1"></i>En cours
                                </span>
                                @break
                            @case('review')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-eye mr-1"></i>En révision
                                </span>
                                @break
                            @case('done')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Terminé
                                </span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @switch($task->priority)
                            @case('low')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-arrow-down mr-1"></i>Faible
                                </span>
                                @break
                            @case('medium')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-minus mr-1"></i>Moyenne
                                </span>
                                @break
                            @case('high')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-arrow-up mr-1"></i>Élevée
                                </span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($task->due_date)
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                <span class="{{ $task->due_date < now() && $task->status !== 'done' ? 'text-red-600' : '' }}">
                                    {{ $task->due_date->format('d/m/Y') }}
                                </span>
                            </div>
                        @else
                            <span class="text-gray-400">Non définie</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('tasks.show', $task) }}" 
                               class="text-blue-600 hover:text-blue-900" title="Voir la tâche">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('tasks.edit', $task) }}" 
                               class="text-yellow-600 hover:text-yellow-900" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteTask({{ $task->id }}, '{{ $task->title }}')" 
                                    class="text-red-600 hover:text-red-900" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-tasks text-4xl text-gray-300 mb-4"></i>
                            <p class="text-lg font-medium">Aucune tâche trouvée</p>
                            <p class="text-sm">Commencez par créer votre première tâche</p>
                            <a href="{{ route('tasks.create') }}" class="btn-primary-modern mt-4">
                                <i class="fas fa-plus mr-2"></i>
                                Créer une tâche
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($tasks->hasPages())
        <div class="mt-6">
            {{ $tasks->links() }}
        </div>
    @endif
</div>

<!-- Vue calendrier -->
<div id="calendar-view" class="modern-card hidden">
    <div id="calendar" class="h-96"></div>
</div>

<!-- Statistiques rapides -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
    <div class="modern-card text-center">
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-tasks text-blue-600 text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $tasks->total() }}</h3>
        <p class="text-sm text-gray-600">Total tâches</p>
    </div>
    
    <div class="modern-card text-center">
        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-check text-green-600 text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $tasks->where('status', 'done')->count() }}</h3>
        <p class="text-sm text-gray-600">Terminées</p>
    </div>
    
    <div class="modern-card text-center">
        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-play text-yellow-600 text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $tasks->where('status', 'in_progress')->count() }}</h3>
        <p class="text-sm text-gray-600">En cours</p>
    </div>
    
    <div class="modern-card text-center">
        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $tasks->where('due_date', '<', now())->where('status', '!=', 'done')->count() }}</h3>
        <p class="text-sm text-gray-600">En retard</p>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
let isListView = true;

function toggleView() {
    const listView = document.getElementById('list-view');
    const calendarView = document.getElementById('calendar-view');
    const viewIcon = document.getElementById('view-icon');
    const viewText = document.getElementById('view-text');
    
    isListView = !isListView;
    
    if (isListView) {
        listView.classList.remove('hidden');
        calendarView.classList.add('hidden');
        viewIcon.className = 'fas fa-calendar-alt mr-2';
        viewText.textContent = 'Vue calendrier';
    } else {
        listView.classList.add('hidden');
        calendarView.classList.remove('hidden');
        viewIcon.className = 'fas fa-list mr-2';
        viewText.textContent = 'Vue liste';
        
        // Initialiser le calendrier si pas encore fait
        if (!document.getElementById('calendar').hasChildNodes()) {
            initCalendar();
        }
    }
}

function initCalendar() {
    const calendarEl = document.getElementById('calendar');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        events: [
            @foreach($tasks as $task)
                @if($task->due_date)
                {
                    title: '{{ $task->title }}',
                    start: '{{ $task->due_date->format('Y-m-d') }}',
                    color: '{{ $task->status === "done" ? "#10B981" : ($task->due_date < now() ? "#EF4444" : "#3B82F6") }}',
                    url: '{{ route("tasks.show", $task) }}'
                },
                @endif
            @endforeach
        ],
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            window.location.href = info.event.url;
        }
    });
    
    calendar.render();
}

function filterTasks() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const projectFilter = document.getElementById('project_filter').value;
    const statusFilter = document.getElementById('status_filter').value;
    const assigneeFilter = document.getElementById('assignee_filter').value;
    
    const rows = document.querySelectorAll('.task-row');
    
    rows.forEach(row => {
        const title = row.dataset.title;
        const project = row.dataset.project;
        const status = row.dataset.status;
        const assignee = row.dataset.assignee;
        
        const matchesSearch = title.includes(searchTerm);
        const matchesProject = projectFilter === '' || project === projectFilter;
        const matchesStatus = statusFilter === '' || status === statusFilter;
        const matchesAssignee = assigneeFilter === '' || assignee === assigneeFilter;
        
        if (matchesSearch && matchesProject && matchesStatus && matchesAssignee) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function resetFilters() {
    document.getElementById('search').value = '';
    document.getElementById('project_filter').value = '';
    document.getElementById('status_filter').value = '';
    document.getElementById('assignee_filter').value = '';
    filterTasks();
}

function deleteTask(taskId, taskTitle) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer la tâche "${taskTitle}" ?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/tasks/${taskId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
