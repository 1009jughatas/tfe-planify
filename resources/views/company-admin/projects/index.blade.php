@extends('company-admin.layouts.app')

@section('title', 'Gestion des projets')
@section('page-title', 'Gestion des projets')

@section('content')
<!-- En-tête avec bouton d'action -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Gestion des projets</h2>
        <p class="text-gray-600 mt-1">Gérez tous les projets de votre équipe</p>
    </div>
    <a href="{{ route('projects.create') }}" class="btn-primary-modern mt-4 sm:mt-0">
        <i class="fas fa-plus mr-2"></i>
        Créer un projet
    </a>
</div>

<!-- Filtres et recherche -->
<div class="modern-card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
            <input type="text" id="search" placeholder="Nom du projet..." 
                   class="input-modern" onkeyup="filterProjects()">
        </div>
        <div>
            <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <select id="status_filter" class="input-modern" onchange="filterProjects()">
                <option value="">Tous les statuts</option>
                <option value="planning">En planification</option>
                <option value="active">Actif</option>
                <option value="on_hold">En pause</option>
                <option value="completed">Terminé</option>
            </select>
        </div>
        <div>
            <label for="author_filter" class="block text-sm font-medium text-gray-700 mb-1">Créateur</label>
            <select id="author_filter" class="input-modern" onchange="filterProjects()">
                <option value="">Tous les créateurs</option>
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

<!-- Liste des projets -->
<div class="modern-card">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créateur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membres</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progression</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($projects as $project)
                <tr class="project-row" 
                    data-name="{{ strtolower($project->name) }}" 
                    data-status="{{ $project->status }}" 
                    data-author="{{ $project->author_id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-folder text-white text-sm"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($project->description, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-xs">{{ substr($project->author->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $project->author->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex -space-x-2">
                            @forelse($project->participants->take(3) as $participant)
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center border-2 border-white">
                                    <span class="text-white font-semibold text-xs">{{ substr($participant->name, 0, 1) }}</span>
                                </div>
                            @empty
                                <span class="text-sm text-gray-500">Aucun membre</span>
                            @endforelse
                            @if($project->participants->count() > 3)
                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center border-2 border-white">
                                    <span class="text-gray-600 font-semibold text-xs">+{{ $project->participants->count() - 3 }}</span>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @switch($project->status)
                            @case('planning')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Planification
                                </span>
                                @break
                            @case('active')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-play mr-1"></i>Actif
                                </span>
                                @break
                            @case('on_hold')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-pause mr-1"></i>En pause
                                </span>
                                @break
                            @case('completed')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Terminé
                                </span>
                                @break
                            @default
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $project->status }}
                                </span>
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($project->deadline)
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                <span class="{{ $project->deadline < now() && $project->status !== 'completed' ? 'text-red-600' : '' }}">
                                    {{ $project->deadline->format('d/m/Y') }}
                                </span>
                            </div>
                        @else
                            <span class="text-gray-400">Non définie</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $totalTasks = $project->tasks()->count();
                            $completedTasks = $project->tasks()->where('status', 'done')->count();
                            $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                        @endphp
                        <div class="flex items-center">
                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600">{{ $progress }}%</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">{{ $completedTasks }}/{{ $totalTasks }} tâches</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('projects.show', $project) }}" 
                               class="text-blue-600 hover:text-blue-900" title="Voir le projet">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('projects.tasks', $project) }}" 
                               class="text-green-600 hover:text-green-900" title="Voir les tâches">
                                <i class="fas fa-tasks"></i>
                            </a>
                            <a href="{{ route('projects.edit', $project) }}" 
                               class="text-yellow-600 hover:text-yellow-900" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteProject({{ $project->id }}, '{{ $project->name }}')" 
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
                            <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                            <p class="text-lg font-medium">Aucun projet trouvé</p>
                            <p class="text-sm">Commencez par créer votre premier projet</p>
                            <a href="{{ route('projects.create') }}" class="btn-primary-modern mt-4">
                                <i class="fas fa-plus mr-2"></i>
                                Créer un projet
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($projects->hasPages())
        <div class="mt-6">
            {{ $projects->links() }}
        </div>
    @endif
</div>

<!-- Statistiques rapides -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
    <div class="modern-card text-center">
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-folder text-blue-600 text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $projects->total() }}</h3>
        <p class="text-sm text-gray-600">Total projets</p>
    </div>
    
    <div class="modern-card text-center">
        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-play text-green-600 text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $projects->where('status', 'active')->count() }}</h3>
        <p class="text-sm text-gray-600">Projets actifs</p>
    </div>
    
    <div class="modern-card text-center">
        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-clock text-yellow-600 text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $projects->where('status', 'planning')->count() }}</h3>
        <p class="text-sm text-gray-600">En planification</p>
    </div>
    
    <div class="modern-card text-center">
        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-check text-purple-600 text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $projects->where('status', 'completed')->count() }}</h3>
        <p class="text-sm text-gray-600">Terminés</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterProjects() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const statusFilter = document.getElementById('status_filter').value;
    const authorFilter = document.getElementById('author_filter').value;
    
    const rows = document.querySelectorAll('.project-row');
    
    rows.forEach(row => {
        const name = row.dataset.name;
        const status = row.dataset.status;
        const author = row.dataset.author;
        
        const matchesSearch = name.includes(searchTerm);
        const matchesStatus = statusFilter === '' || status === statusFilter;
        const matchesAuthor = authorFilter === '' || author === authorFilter;
        
        if (matchesSearch && matchesStatus && matchesAuthor) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function resetFilters() {
    document.getElementById('search').value = '';
    document.getElementById('status_filter').value = '';
    document.getElementById('author_filter').value = '';
    filterProjects();
}

function deleteProject(projectId, projectName) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le projet "${projectName}" ?\n\nCette action supprimera également toutes les tâches associées et ne peut pas être annulée.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/projects/${projectId}`;
        
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
